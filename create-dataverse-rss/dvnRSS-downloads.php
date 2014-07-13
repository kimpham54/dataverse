<?php 

header("Content-Type: application/rss+xml; charset=utf-8"); 

echo '<?xml version="1.0" encoding="utf-8" ?>';
?> 


<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">  
<channel>
<title>Your Dataverse - Most Downloaded Studies</title>
<link>http://linktoyourdataverse.com</link>
<description>Most Downloaded Studies</description>

    <?php  
        $conn=pg_connect("host=http://linktoyourdataverse.com port=5432 dbname=Your_DBName user=Your_Username password=Your_Password");
    // Check connection
    if (!$conn) {
  echo "An error occurred.\n";
  exit;
}

        $result = pg_query($conn,"select sum(studyfileactivity.downloadcount), metadata.title, study.studyid
from public.metadata, public.studyversion, public.studyfileactivity, public.study, public.vdc
where metadata.id = studyversion.metadata_id 
and study.id = studyfileactivity.study_id
and studyversion.study_id = study.id 
and studyversion.versionstate='RELEASED'
and study.owner_id = vdc.id
and vdc.releasedate is not null
group by metadata.title, study.studyid
order by sum(studyfileactivity.downloadcount) desc
limit 5");
        //you can change query for most downloaded studies (ORDER BY downloadCount DESC LIMIT), change query for recently added studies (ORDER BY releaseTIME DESC LIMIT)  
        while ($row=pg_fetch_array($result)){


    ?>  
    <item>  
    <title><?php echo $row[1]; ?></title>
	<description><?php echo $row[0]; ?> downloads</description> 	
    <link>http://hdl.handle.net/Your_DVN#/<?php echo $row[2]; ?></link>   <!--link from metadata.title-->
    </item>  
    <?php  
	
    }  
    ?>  

</channel>  
</rss>  