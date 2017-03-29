<html>
    <head>
        <title>PHP Solr client</title>

        <style type="text/css">
            #center {
                margin-left: 250px;
            }
        </style>
        <script type="text/javascript">
            function validateForm()
            {
                var query_btn = document.getElementById("query");
                var alertString = "Please enter a query to be searched !!!";
                
                if(query_btn.value.trim() == "")
                {
                    alert(alertString);
                    query_btn.focus();
                    query_btn.select();
                    return;
                }
            }
            
            function resetForm(form)
            {
                window.location="solrAss3.php";
            }
        </script>
    </head>
    
    <body>
    <!--    <div id="div1"> -->
            <h2 style="text-align:center; margin-top:-40px;">Solr Query Search Result</h2>
            <form id="solrForm" name="form" method="get" accept-charset="utf-8">
    <!--    <form id="solrForm" name="form" method="get" accept-charset="utf-8" action="/solrAss3.php">    -->
                <label for="q">Search:</label>
                <input id="query" name="q" type="text" size="30" placeholder="Enter a Query" value="<? echo isset($_GET["q"])?$_GET["q"]:""?>"/>
       <!--         <br/><br/>  -->
                <input type="radio" id="solr" name="ranking" value="solr" checked <?php echo isset($_GET["ranking"]) && $_GET["ranking"] == "solr"?"checked":"";?>/>Solr
                <input type="radio" id="PageRank" name="ranking" value="PageRank" <?php echo isset($_GET["ranking"]) && $_GET["ranking"] == "PageRank"?"checked":""; ?>/>PageRank
                <br><br>
                <input id="center" type="submit" onclick="validateForm();"/>
                <input type="reset" onclick="resetForm();"/>
            </form>
   <!--     </div> -->
        
        <?php
            $include = 'solr-php-client/Apache/Solr/Service.php';
            $domain = 'localhost';
            $port = 8983;
            $core = '/solr/myexample';
                       
            $limit = 10;
            $query = isset($_REQUEST['q']) ? $_REQUEST['q'] : false;
            $results = false;
            $ranking = $_REQUEST['ranking'];
            //echo $query;
            //echo $ranking;
        
            $additionalParameters = array(
                                            'sort' => 'pageRankFile desc'
                                        );
        
            if ($query)
            {
                  // The Apache Solr Client library should be on the include path
                  // which is usually most easily accomplished by placing in the
                  // same directory as this script ( . or current directory is a default
                  // php include path entry in the php.ini)
                  require_once($include);

                  // create a new solr service instance - host, port, and webapp
                  // path (all defaults in this example)
                  $solr = new Apache_Solr_Service($domain, $port, $core);

                  // if magic quotes is enabled then stripslashes will be needed
                  if (get_magic_quotes_gpc() == 1)
                  {
                    $query = stripslashes($query);
                  }

                  // in production code you'll always want to use a try /catch for any
                  // possible exceptions emitted  by searching (i.e. connection
                  // problems or a query parsing error)
                  try
                  {
                      if($ranking == "solr")
                        $results = $solr->search($query, 0, $limit);
                      else if($ranking == "PageRank")
                        $results = $solr->search($query, 0, $limit, $additionalParameters);
                  }
                  catch (Exception $e)
                  {
                    // in production you'd probably log or email this error to an admin
                    // and then show a special message to the user but for this example
                    // we're going to show the full exception
                    die("<html><head><title>SEARCH EXCEPTION</title><body><pre>{$e->__toString()}</pre></body></html>");
                  }
            }
        ?>
        <?php
            // display results
            if ($results)
            {
                  $total = (int) $results->response->numFound;
                  $start = min(1, $total);
                  $end = min($limit, $total);
        ?>   
                <div id="result">Results <?php echo $start; ?> - <?php echo $end;?> of <?php echo $total; ?>:
                <ol>
        <?php
                // iterate result documents
                foreach ($results->response->docs as $doc)
                {
        ?>
        <?php
                    //id
                    $id = $doc->id;
                    $pos = strrpos($id, "/");
                    $id = substr($id,$pos+1);
                    $id = str_replace("--","/",$id);
                    //echo $id;
                    //Title
                    $title = $doc->title;
                    $title = str_replace("//","|",$title);
                    if($title == null)
                        $title = "N/A";
                    
                    //url
                    $url = $doc->og_url;
                    
                    //file size
                    $fileSize = ($doc->stream_size/1000)."KB";
                    
                    //Author
                    $author = $doc->author;
                    if($author == null)
                        $author = "N/A";
                    
                    //formatting the date
                    $date = $doc->dcterms_created;
                    $pos1 = strrpos($date,"T");
                    $date = substr($date,0,$pos1);
                    if($date == null)
                        $date = "N/A";
                    
        ?>
                    <li>
                        <p><a href="<?php echo $id; ?>" target="_blank">Document</a> <?php echo $title; ?> </p>
                        <p><b>Size:</b> <?php echo $fileSize; ?>; <b>Author:</b> <?php echo $author; ?>; <b>date_created:</b> <?php echo $date; ?></p>
                    </li>
                   
        <?php
                }
        ?>
            </ol>
        <?php
            }
        ?>
            </div>
        
    </body>
</html>