<?php 
header("Content-Type: application/rss+xml; charset=utf-8"); 

echo '<?xml version="1.0" encoding="utf-8" ?>';
?> 


<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">  
<channel>
<title>Your Dataverse - Recently Released Studies</title>
<link>http://linktoyourdataverse.com</link>
<description>Recently Released Studies</description>

    <?php  
        $conn=pg_connect("host=http://linktoyourdataverse.com port=5432 dbname=dvnDb user=readonly password=SP_readGood");
    // Check connection
    if (!$conn) {
  echo "An error occurred.\n";
  exit;
}

        $result = pg_query($conn,"select distinct vdc.releasedate, metadata.title, study.studyid, studyversion.* 
from public.studyversion, public.metadata, public.study, public.vdc
where studyversion.metadata_id = metadata.id 
and versionstate='RELEASED' 
and study.id = studyversion.study_id 
and study.owner_id = vdc.id
and vdc.releasedate is not null
order by studyversion.createtime DESC
limit 5");
        //you can change query for most downloaded studies (ORDER BY downloadCount DESC LIMIT), change query for recently added studies (ORDER BY releaseTIME DESC LIMIT)  
        while ($row=pg_fetch_array($result)){


    ?>  
    <item>  
    <title><![CDATA[<?php echo $row[1]; ?>]]></title>  
    <link>http://hdl.handle.net/Your_DVN#/<?php echo $row[2]; ?></link>  <!--link from metadata.title-->
    </item>  
    <?php  
	
    }  
    ?>  

</channel>  
</rss>  