USE [cimnc]
GO
/****** Object:  StoredProcedure [dbo].[P_GetPrePaperTotal]    Script Date: 09/08/2018 09:49:44 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE  proc [dbo].[P_GetPrePaperTotal] (
@tablename varchar(10),
@wherestr varchar(50)
)
as 

begin
SET NOCOUNT ON;

if not (select object_id('Tempdb..#PaperTotal')) is null drop table #PaperTotal

create table #PaperTotal
(
 sn nvarchar(5),
 width nvarchar(10),
 paper nvarchar(20),
 flute_type nvarchar(5),
 total_len int  
)

declare @prodwid int,@paper nvarchar(20),@flute char(3),@tlen int 
declare @prodwid1 int,@paper1 nvarchar(20),@flute1 char(3),@len1 int,@tlen1 int
declare @sqlstr nvarchar(400)
declare @totalstr nvarchar(20)
declare @sn int
set @sqlstr = 'declare OrderInfoCursor cursor for select 门幅 width,生产纸质 paper,楞别 flute_type,(纸长*切刀数/1000) tlen  from bc order by 序号 '
exec( @sqlstr )

open OrderInfoCursor
fetch NEXT from OrderInfoCursor into @prodwid,@paper,@flute,@tlen
set @prodwid1= @prodwid
set @paper1=@paper
set @flute1=@flute
set @len1 = @tlen 
set @tlen1 = @tlen 
set @sn=1

while @@fetch_status=0
begin
   fetch next from OrderInfoCursor into @prodwid,@paper,@flute,@tlen
   if @@fetch_status=0
   begin
	  if ( @prodwid1 = @prodwid) 
		begin
		  set @tlen1 = @tlen+@tlen1
		  if (@flute1=@flute)and(@paper=@paper1)
				begin
				set @len1 = @tlen+@len1
				end
		  else
			begin
				insert into #PaperTotal ( sn,width,paper,flute_type,total_len ) VALUES ( @sn,@prodwid1,@paper1,@flute1,@len1 )
				set @prodwid1=@prodwid
				set @paper1=@paper
				set @flute1=@flute
				set @len1 = @tlen
				set @sn=@sn+1
			end
		end
	 else
	 	begin
			insert into #PaperTotal ( sn,width,paper,flute_type,total_len ) VALUES ( @sn,@prodwid1,@paper1,@flute1,@len1 )
			set @sn=@sn+1
			--set @totalstr ='小计:' + cast( @tlen1 as varchar(10) )
			insert into #PaperTotal ( sn,width,paper,flute_type,total_len ) VALUES ( @sn,@prodwid1,'小计:','',@tlen1 )
			set @prodwid1=@prodwid
			set @paper1=@paper
			set @flute1=@flute
			set @len1 = @tlen
			set @tlen1 = @tlen
			set @sn=@sn+1	
		end
	  
   end
end

insert into #PaperTotal ( sn,width,paper,flute_type,total_len ) VALUES ( @sn,@prodwid1,@paper1,@flute1,@len1 )
--set @totalstr = '小计:   ' +cast( @tlen1 as varchar(10) )
set @sn=@sn+1	
insert into #PaperTotal ( sn,width,paper,flute_type,total_len ) VALUES ( @sn,@prodwid1,'小计:','',@tlen1 )

close OrderInfoCursor
deallocate OrderInfoCursor

select * from #PaperTotal --order by sn

end
GO
/****** Object:  View [dbo].[view_prefinish]    Script Date: 09/08/2018 09:49:42 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW [dbo].[view_prefinish]
AS
select 1 tag, null as sch_id,sn,order_number, company_name,paper,width,flute_type,paper_len,paper_w,quantity,cutting_qty,
total_len,slitting1,slitting_data1,trimming,bundle_qty,packaging_qty,remain_totallen,
pre_finishtime =Dateadd(ss,remain_totallen*60/ (case when avgspeed>0 then avgspeed else 120 end) ,getdate())
 from (select 序号 as sn,订单号 as order_number,生产纸质 as paper,客户名称 as company_name,
门幅 as width,楞别 as flute_type,纸长 as paper_len,纸宽 as paper_w,订单数 as quantity,切刀数 as cutting_qty,
总长 as total_len,剖1 as slitting1,压线资料1 as slitting_data1,修边 as trimming, 错位捆数 as bundle_qty,捆数 as packaging_qty,
avgspeed=  ( select CONVERT(int,ISNULL(sum(lenmm*1.0*prod/1000),0) /((sum(prodtime+stoptime)*1.0/60)+0.001),0) avg_speed from finish where classno=(select top 1 班别 from classno order by 换班时间 desc)),
 remain_totallen= (select sum(切刀数*纸长)/1000 from bc b where b.序号 <= a.序号)  
from bc a) a
GO
/****** Object:  View [dbo].[view_myorder]    Script Date: 09/08/2018 09:49:42 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE view  [dbo].[view_myorder]
AS
select 1 tag, [id],[sn],order_number,RIGHT([order_number],7) as [short_order_number],[company_name],
	[order_date],[order_source],[storage_area],
	[line],[paper],[flute_type],[width],[paper_len],[paper_w],[quantity],[cutting_qty], [paper_len]*[cutting_qty]/1000 as [total_len],[max_speed],
	[pressing_type],[slitting1],[slitting_data1],[slitting2],[slitting_data2],[slitting3],[slitting_data3],
	[trimming],[bundle_qty],[packaging_qty],
	[start_time],[note],
	totallen_i,
	pre_finishtime =Dateadd(ss,totallen_i*60/ (case when avgspeed>0 then avgspeed else 120 end) ,getdate())
	from (select [id],序号 as [sn],订单号 as [order_number],客户名称 as [company_name],
		下单时间 as [order_date],用户 as [order_source],存放区域 as [storage_area],
		线别 as [line],生产纸质 as [paper],楞别 as [flute_type],门幅 as [width],纸长 as [paper_len],纸宽 as [paper_w],
		订单数 as [quantity],切刀数 as [cutting_qty],[纸长]*[切刀数]/1000 as [total_len],限速 as [max_speed],
		压型 as [pressing_type],剖1 as [slitting1],压线资料1 as [slitting_data1],剖2 as [slitting2],压线资料2 as [slitting_data2],剖3 as [slitting3],压线资料3 as [slitting_data3],
		修边 as [trimming],错位捆数 as [bundle_qty],捆数 as [packaging_qty],
		开始时间 as [start_time],生产备注 as [note],
		avgspeed=  ( select CONVERT(int,ISNULL(sum(lenmm*1.0*prod/1000),0) /((sum(prodtime+stoptime)*1.0/60)+0.001),0) avg_speed from finish where classno=(select top 1 班别 from classno order by 换班时间 desc)),
		totallen_i= (select sum(切刀数*纸长)/1000 from bc b where b.序号 <= a.序号)  
from bc a) a
GO
/****** Object:  View [dbo].[view_finish]    Script Date: 09/08/2018 09:49:42 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE view  [dbo].[view_finish]
AS

select 1 tag, [id],[sn],orderno as [order_number],custname as [company_name],
username as [order_source],area as [storage_area],[paper],lb as [flute_type],prodwid as [width],wid as [paper_w],
lenmm as [paper_len],ordnum as [order_qty],cutnum as [cutting_qty],lstspeed as [max_speed],yx as [pressing_type],
cut1 as [slitting1],cutdata1 as [slitting_data1],cut2 as [slitting2],cutdata2 as [slitting_data2],cut3 as [slitting3],cutdata3 as [slitting_data3],
xb as [trimming],bundlenum as [bundle_qty],unit as [packaging_qty],
begindate as [start_time],mem as [note],[line],classno as [shift_code],finishdate as [finish_date],
prod as [good_qty],bad as [bad_qty],prodlen as [good_len],
SF1len as [UsePaperLen1],SF2len as [UsePaperLen2],SF3len as [UsePaperLen3],SF4len as [UsePaperLen4],
arvspeed as [avg_speed],prodtime as [work_time],stoptime as [stop_time],[stops] from [Finish]
GO
/****** Object:  StoredProcedure [dbo].[P_GetFlute]    Script Date: 09/08/2018 09:49:44 ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE proc [dbo].[P_GetFlute]
AS

BEGIN
SET NOCOUNT ON;

DECLARE @fluteType1 nvarchar(1),@fluteType2 nvarchar(1),@fluteType3 nvarchar(1),@fluteType4 nvarchar(1),
@fluteRate1 decimal(18, 3),@fluteRate2 decimal(18, 3),@fluteRate3 decimal(18, 3),@fluteRate4 decimal(18, 3)

select top 1  @fluteType1=sf1wave, @fluteType2=sf2wave, @fluteType3=sf3wave from speedtime

SET @fluteRate1= convert(decimal(18, 3),ISNULL((select top 1 楞率 from  lb where RTRIM(楞型)=RTRIM(@fluteType1)),1))/100
SET @fluteRate2=convert(decimal(18, 3),ISNULL((select top 1 楞率 from  lb where RTRIM(楞型)=RTRIM(@fluteType2)),1))/100
SET @fluteRate3=convert(decimal(18, 3),ISNULL((select top 1 楞率 from  lb where RTRIM(楞型)=RTRIM(@fluteType3)),1))/100
SET @fluteRate4=1

select ISNULL(@fluteRate1,1) as FluteRate1,ISNULL(@fluteRate2,1) as FluteRate2,ISNULL(@fluteRate3,1)as FluteRate3,ISNULL(@fluteRate4,1)as FluteRate4

select @fluteType1 as FluteType1,@fluteType2 as FluteType2,@fluteType3 as FluteType3,@fluteType4 as FluteType4

END
GO
