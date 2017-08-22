<? if($pageRecordCount > 0 ) { ?>

<style>

/*

.tdPaging1

{

	border-left:1px solid #333333;

	border-top:1px solid #333333;

	border-bottom:1px solid #333333;

	border-right:0px solid #333333;

}

.tdPaging2

{

	border-left:1px solid #333333;

	border-top:1px solid #333333;

	border-bottom:1px solid #333333;

	border-right:0px solid #333333;



}

.tdPaging

{

	border-left:1px solid #333333;

	border-top:1px solid #333333;

	border-bottom:1px solid #333333;

	border-right:0px solid #333333;

}

*/

.tdPagingCurrent{

	background-color:#C6D7EF;

	color:#FFFFFF;

	/*

	border-left:1px solid #333333;

	border-top:1px solid #333333;

	border-bottom:1px solid #333333;

	border-right:0px solid #333333;

	*/

}

/*

.tdPaging3

{

	border-left:1px solid #333333;

	border-top:1px solid #333333;

	border-bottom:1px solid #333333;

	border-right:0px solid #333333;

}

.tdPaging4

{

	border-left:1px solid #333333;

	border-top:1px solid #333333;

	border-bottom:1px solid #333333;

	border-right:1px solid #333333;

}



.pagelinkCurrent

{

	font-weight:bold;

	background-color:#4A658C;

	color:#FFFFFF;	

	text-decoration: none;

}

.pagelink

{

	font-weight:normal;

	text-decoration: none;

}



.pagelinkCurrent:hover

{

	font-weight:bold;

	background-color:#4A658C;

	color:#FFFFFF;

	text-decoration: underline;	

}

.pagelink:hover

{

	font-weight:normal;

	text-decoration: underline;

}

*/

</style>



<table border="0" cellspacing="0" cellpadding="0">

  <tr>

  

  	<td>

	<? 

	/*$pos=strpos($URL,"webadmin");

	if(!($pos===false))

	{*/

	?>

	[ Showing <?=$startRecord?> to <?=($start+$pageRecordCount)?> of <?=$totalRecordCount?> ]&nbsp;&nbsp;<? /*}*/?>

	</td>

    <td> &nbsp; Records Per page :

	<? if($dpp === true) { ?>

	<select name="dpp" size="1" id="dpp" onChange="javascript:dppList(this.value);">

          <option value="10" <?=(($dataPerPage==10)?'selected':'')?> >10</option>

          <option value="25" <?=(($dataPerPage==25)?'selected':'')?> >25</option>

          <option value="50" <?=(($dataPerPage==50)?'selected':'')?> >50</option>

          <option value="100" <?=(($dataPerPage==100)?'selected':'')?> >100</option>

    </select>

	<? } ?>

	</td>

	<td>

		&nbsp;&nbsp; Go to Page no. : <select onChange="javascript:window.location='<?=$URL."?pg="?>'+this.value+'<?=$extraParam?>';" style="font-family:verdana; font-size:11px; width:50px;">

		<? for($i=1;$i<=$totalPageCount;$i++){?>

			<option value="<?=$i;?>" <?=(isset($currentPage) && $currentPage==$i)?"selected":"";?>><?=$i;?></option>

		<? }?>

		</select>

	</td>

	<td class="tdPaging1" >&nbsp;<a href="<?=$prevSegment?>" class="pagelink" >&lt;&lt;</a>&nbsp;</td>

    <td class="tdPaging2" >&nbsp;<a href="<?=$prevPage?>" class="pagelink" >&lt;</a>&nbsp;</td>

	<? 	$cnt = 0;	

		for($i=$startPage;$i<=$endPage;$i++) 

		{ 

			$cnt++;

			$linkClass = "pagelink"; if($currentPage == $i) {$linkClass = "pagelinkCurrent";} 

			$tdClass = "tdPaging"; if($currentPage == $i) {$tdClass = "tdPagingCurrent";} 

			$page = $i; if($i<10) {$page = '0'.$i;}

	?>

	<td class="<?=$tdClass?>" >&nbsp;<a href="<?=$URL."?pg=".$i.$extraParam?>" class="<?=$linkClass?>" ><?=($page)?></a>&nbsp;</td>

	<? } ?>

	<!-- 

	<? 		

		for($i=$cnt;$i<$pageSegmentSize;$i++) 

		{ 

			$tdClass = "tdPaging"; if($currentPage == $i) {$tdClass = "tdPagingCurrent";} 

	?>

	<td class="<?=$tdClass?>" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>

	<? } ?>

	-->

	<td class="tdPaging3">&nbsp;<a href="<?=$nextPage?>" class="pagelink" >&gt;</a>&nbsp;</td>

    <td class="tdPaging4">&nbsp;<a href="<?=$nextSegment?>" class="pagelink" >&gt;&gt;</a>&nbsp;</td>

  </tr>

</table>

<? } else { ?>

<table border="0" cellspacing="0" cellpadding="0">

  <tr>

  	<td><!--[ Showing <?=$startRecord?> to <?=($start+$pageRecordCount)?> of <?=$totalRecordCount?> ]&nbsp;&nbsp;-->

	No Record Found...</td>

  </tr>

</table>

<? } ?>