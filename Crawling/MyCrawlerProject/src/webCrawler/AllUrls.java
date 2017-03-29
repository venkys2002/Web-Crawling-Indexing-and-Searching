package webCrawler;
import java.io.IOException;

import edu.uci.ics.crawler4j.crawler.Page;
import edu.uci.ics.crawler4j.url.WebURL;


public class AllUrls {

	public void writeAllUrls(Page page,WebURL URL)
	{
		String url = URL.getURL();
		
		String indicator = new String();

		if(url.startsWith("http://priceschool.usc.edu/"))
		{
			indicator = "OK";
			MyCrawler.school_Price.add(url);
		}
		else if(!url.startsWith("http://priceschool.usc.edu/") && url.contains(".usc.edu"))
		{
			indicator = "USC";
			MyCrawler.school_USC.add(url);
		}
		else
		{
			indicator = "outUSC";
			MyCrawler.school_Outside.add(url);
		}

		String[] values = {url, indicator};
		
		Controller.allCSV.writeNext(values);
	}
	
	public void closeFile()
	{
		try 
		{
			Controller.allCSV.close();
		} 
		catch (IOException e) 
		{
			e.printStackTrace();
		}
	}
}
