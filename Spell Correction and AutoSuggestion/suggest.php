<?php
    include 'stemmer.php';

    $stopwords = array("a", "about", "above", "above", "across", "after", "afterwards", "again", "against", "all", "almost", "alone",   "along", "already", "also","although","always","am","among", "amongst", "amoungst", "amount",  "an", "and", "another", "any","anyhow","anyone","anything","anyway", "anywhere", "are", "around", "as",  "at", "back","be","became", "because","become","becomes", "becoming", "been", "before", "beforehand", "behind", "being", "below", "beside", "besides", "between", "beyond", "bill", "both", "bottom","but", "by", "call", "can", "cannot", "cant", "co", "con", "could", "couldnt", "cry", "de", "describe", "detail", "do", "done", "down", "due", "during", "each", "eg", "eight", "either", "eleven","else", "elsewhere", "empty", "enough", "etc", "even", "ever", "every", "everyone", "everything", "everywhere", "except", "few", "fifteen", "fify", "fill", "find", "fire", "first", "five", "for", "former", "formerly", "forty", "found", "four", "from", "front", "full", "further", "get", "give", "go", "had", "has", "hasnt", "have", "he", "hence", "her", "here", "hereafter", "hereby", "herein", "hereupon", "hers", "herself", "him", "himself", "his", "how", "however", "hundred", "ie", "if", "in", "inc", "indeed", "interest", "into", "is", "it", "its", "itself", "keep", "last", "latter", "latterly", "least", "less", "ltd", "made", "many", "may", "me", "meanwhile", "might", "mill", "mine", "more", "moreover", "most", "mostly", "move", "much", "must", "my", "myself", "name", "namely", "neither", "never", "nevertheless", "next", "nine", "no", "nobody", "none", "noone", "nor", "not", "nothing", "now", "nowhere", "of", "off", "often", "on", "once", "one", "only", "onto", "or", "other", "others", "otherwise", "our", "ours", "ourselves", "out", "over", "own","part", "per", "perhaps", "please", "put", "rather", "re", "same", "see", "seem", "seemed", "seeming", "seems", "serious", "several", "she", "should", "show", "side", "since", "sincere", "six", "sixty", "so", "some", "somehow", "someone", "something", "sometime", "sometimes", "somewhere", "still", "such", "system", "take", "ten", "than", "that", "the", "their", "them", "themselves", "then", "thence", "there", "thereafter", "thereby", "therefore", "therein", "thereupon", "these", "they", "thickv", "thin", "third", "this", "those", "though", "three", "through", "throughout", "thru", "thus", "to", "together", "too", "top", "toward", "towards", "twelve", "twenty", "two", "un", "under", "until", "up", "upon", "us", "very", "via", "was", "we", "well", "were", "what", "whatever", "when", "whence", "whenever", "where", "whereafter", "whereas", "whereby", "wherein", "whereupon", "wherever", "whether", "which", "while", "whither", "who", "whoever", "whole", "whom", "whose", "why", "will", "with", "within", "without", "would", "yet", "you", "your", "yours", "yourself", "yourselves", "the");


    $query = strtolower($_GET['q']);
    $arrayQuery = explode(" ",trim($query));
    $size = count($arrayQuery);

    //extract the last word to get the suggester
    $str = "";
    for($i=0;$i<$size;$i++)
    {
        if($i == $size-1)
            $str .= $arrayQuery[$i];
    }
    //echo $str;
    
    $result = file_get_contents("http://localhost:8983/solr/myexample/suggest?q=".$str."&wt=json&indent=true&sort=weight%20desc");
    $json_o = json_decode($result,true);

    $res = array();
    $temp = "";
    for($i=0;$i<$size-1;$i++)
    {
        $temp .= $arrayQuery[$i]." ";
    }
    //echo $temp;
    $stemmer = array();
    foreach($json_o["suggest"]["suggest"][$str]["suggestions"] as $term)
    {
        $stemValue = PorterStemmer::Stem($term["term"]);
        if(in_array($stemValue,$stemmer))
            continue;

        if(!($term["term"] == $str) && (ctype_alnum($term["term"])) && !(in_array($term["term"],$stopwords)))
        {
            array_push($res,$temp." ".$term["term"]);
        }
        
        array_push($stemmer,$stemValue);
        if(count($stemmer) == 6)
            break;
    }
    echo json_encode($res);
    //echo $result."<br>";
    //echo var_dump($arrayQuery); 
?>