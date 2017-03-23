# Errata for 'Automated Data Collection with R'


Last update: 2017-03-21 18:35:59


```r
library(stringr)
library(RCurl)
library(XML)
library(rvest)
```


# page 2

*Credit: Suryapratim Sarkar (2015-06-25)*

Wikipedia changed its server communication from HTTP to HTTPS. As a result, the following lines on page 2 return an error:


```r
heritage_parsed <- htmlParse("http://en.wikipedia.org/wiki/List_of_World_Heritage_in_Danger", 
                             encoding = "UTF-8")
```

```
## Error: failed to load external entity "http://en.wikipedia.org/wiki/List_of_World_Heritage_in_Danger"
```

There are at least two solutions to the problem:

1. Use `getURL()` and specify the location of CA signatures (see Section 9.1.7 of our book).

2. Use Hadley Wickham's `rvest` package, which came out after our book was published. It facilitates scraping with R considerably, in particular in such scenarios. In this specific example, use the following code instead:


```r
library(rvest) # the new package, version 0.3.0
heritage_parsed <- read_html("http://en.wikipedia.org/wiki/List_of_World_Heritage_in_Danger", encoding = "UTF-8") # read_html() from the rvest package is the new htmlParse() from the XML package
tables <- html_table(heritage_parsed, fill = TRUE) # html_table() from the rvest package, which replaces readHTMLTable() from the XML package
```

From thereon, the rest of the chapter code should work. If you want to learn more about the rvest package, have a look [here](http://blog.rstudio.org/2014/11/24/rvest-easy-web-scraping-with-r/). We are planning to cover it extensively in the next edition of our book.




# page 35

*Credit: Jüri Kuusik (2015-01-22)*

typo: change *"parsed_doc"* to *"parsed_fortunes$children"* 



# page 96

*Credit: Tobias Rosenberger (2017-03-14)*

typo: *xmlParse("titles.xml")* should read *xmlParse("books.xml")*.


# page 136

*Credit: Laurent Franckx (2015-02-18)*

Due to (supposedly) a bug in the RCurl package (version 1.95-4.5, bug has been reported) the following lines on page 136 give an error:




```r
handle <- getCurlHandle(customrequest = "HEAD")
res <- getURL(url = url, curl = handle, header = TRUE)
```


There are two workaronds at the moment. 

(1) To make the code simply run through you might give up the HTTP HEAD method used in the code above and use `customrequest = "GET"` instead:


```r
require(RCurl)
require(stringr)

url <- "http://www.r-datacollection.com/materials/http/helloworld.html"
res <- getURL(url = url, header = TRUE)
cat(str_split(res, "\r")[[1]])

handle <- getCurlHandle(customrequest = "GET")
res <- getURL(url = url, curl = handle, header = TRUE)
```


(2) If you want to have a working example involving HTTP HEAD method you might switch to the httr package like this:


```r
require(httr)

url <- "http://www.r-datacollection.com/materials/http/helloworld.html"
res <- HEAD(url)

res
```

```
## Response [http://www.r-datacollection.com/materials/http/helloworld.html]
##   Date: 2015-08-19 15:00
##   Status: 200
##   Content-Type: text/html
## <EMPTY BODY>
```

```r
res$request
```

```
## <request>
## HEAD http://www.r-datacollection.com/materials/http/helloworld.html
## Output: write_memory
## Options:
## * useragent: libcurl/7.24.0 r-curl/0.9.1 httr/1.0.0
## * nobody: TRUE
## * customrequest: HEAD
## Headers:
## * Accept: application/json, text/xml, application/xml, */*
```

```r
res$headers[1:3]
```

```
## $date
## [1] "Wed, 19 Aug 2015 15:00:27 GMT"
## 
## $server
## [1] "Apache"
## 
## $vary
## [1] "Accept-Encoding"
```


# page 194

*Reported by: Laurent Franckx (2015-05-11)*

The URL on page 194 to the parlgov SQLite database has changed and does not work anymore. The new URL is:

http://www.parlgov.org/static/stable/2014/parlgov-stable.db


# page 249

*Reported by: Laurent Franckx (2015-06-08)*

The page structure had changed and code did not work anymore. 





```r
# define urls						   
search_url <- "www.biblio.com/search.php?keyisbn=data"
cart_url   <- "www.biblio.com/cart.php"

# download and parse page
search_page <- htmlParse(getURL(url = search_url, curl = handle))

# identify form fields
xpathApply(search_page, "//div[@class='row-fixed'][position()<2]/form")
```

```
## [[1]]
## <form class="ob-add-form" action="http://www.biblio.com/cart.php" method="get"><input type="hidden" name="bid" value="745162375"/><input type="hidden" name="add" value="1"/><input type="hidden" name="int" value="keyword_search"/><button onclick="_gaq.push(['_trackEvent', 'cart_search_add', 'relevance', '1']);" type="submit" value="Add to cart" class="btn btn-large brown-btn col-md-12 col-xs-12 cart-btn"/><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"/> Add to cart</form> 
## 
## [[2]]
## <form class="ob-add-form" action="http://www.biblio.com/cart.php" method="get"><input type="hidden" name="bid" value="724847744"/><input type="hidden" name="add" value="1"/><input type="hidden" name="int" value="keyword_search"/><button onclick="_gaq.push(['_trackEvent', 'cart_search_add', 'relevance', '2']);" type="submit" value="Add to cart" class="btn btn-large brown-btn col-md-12 col-xs-12 cart-btn"/><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"/> Add to cart</form> 
## 
## [[3]]
## <form class="ob-add-form" action="http://www.biblio.com/cart.php" method="get"><input type="hidden" name="bid" value="755785471"/><input type="hidden" name="add" value="1"/><input type="hidden" name="int" value="keyword_search"/><button onclick="_gaq.push(['_trackEvent', 'cart_search_add', 'relevance', '3']);" type="submit" value="Add to cart" class="btn btn-large brown-btn col-md-12 col-xs-12 cart-btn"/><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"/> Add to cart</form> 
## 
## [[4]]
## <form class="ob-add-form" action="http://www.biblio.com/cart.php" method="get"><input type="hidden" name="bid" value="724420313"/><input type="hidden" name="add" value="1"/><input type="hidden" name="int" value="keyword_search"/><button onclick="_gaq.push(['_trackEvent', 'cart_search_add', 'relevance', '4']);" type="submit" value="Add to cart" class="btn btn-large brown-btn col-md-12 col-xs-12 cart-btn"/><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"/> Add to cart</form> 
## 
## [[5]]
## <form class="ob-add-form" action="http://www.biblio.com/cart.php" method="get"><input type="hidden" name="bid" value="819945705"/><input type="hidden" name="add" value="1"/><input type="hidden" name="int" value="keyword_search"/><button onclick="_gaq.push(['_trackEvent', 'cart_search_add', 'relevance', '5']);" type="submit" value="Add to cart" class="btn btn-large brown-btn col-md-12 col-xs-12 cart-btn"/><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"/> Add to cart</form> 
## 
## [[6]]
## <form class="ob-add-form" action="http://www.biblio.com/cart.php" method="get"><input type="hidden" name="bid" value="724419579"/><input type="hidden" name="add" value="1"/><input type="hidden" name="int" value="keyword_search"/><button onclick="_gaq.push(['_trackEvent', 'cart_search_add', 'relevance', '6']);" type="submit" value="Add to cart" class="btn btn-large brown-btn col-md-12 col-xs-12 cart-btn"/><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"/> Add to cart</form> 
## 
## [[7]]
## <form class="ob-add-form" action="http://www.biblio.com/cart.php" method="get"><input type="hidden" name="bid" value="244413814"/><input type="hidden" name="add" value="1"/><input type="hidden" name="int" value="keyword_search"/><button onclick="_gaq.push(['_trackEvent', 'cart_search_add', 'relevance', '7']);" type="submit" value="Add to cart" class="btn btn-large brown-btn col-md-12 col-xs-12 cart-btn"/><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"/> Add to cart</form> 
## 
## [[8]]
## <form class="ob-add-form" action="http://www.biblio.com/cart.php" method="get"><input type="hidden" name="bid" value="625362790"/><input type="hidden" name="add" value="1"/><input type="hidden" name="int" value="keyword_search"/><button onclick="_gaq.push(['_trackEvent', 'cart_search_add', 'relevance', '8']);" type="submit" value="Add to cart" class="btn btn-large brown-btn col-md-12 col-xs-12 cart-btn"/><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"/> Add to cart</form> 
## 
## [[9]]
## <form class="ob-add-form" action="http://www.biblio.com/cart.php" method="get"><input type="hidden" name="bid" value="648849994"/><input type="hidden" name="add" value="1"/><input type="hidden" name="int" value="keyword_search"/><button onclick="_gaq.push(['_trackEvent', 'cart_search_add', 'relevance', '9']);" type="submit" value="Add to cart" class="btn btn-large brown-btn col-md-12 col-xs-12 cart-btn"/><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"/> Add to cart</form> 
## 
## [[10]]
## <form class="ob-add-form" action="http://www.biblio.com/cart.php" method="get"><input type="hidden" name="bid" value="835578740"/><input type="hidden" name="add" value="1"/><input type="hidden" name="int" value="keyword_search"/><button onclick="_gaq.push(['_trackEvent', 'cart_search_add', 'relevance', '10']);" type="submit" value="Add to cart" class="btn btn-large brown-btn col-md-12 col-xs-12 cart-btn"/><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"/> Add to cart</form> 
## 
## [[11]]
## <form class="ob-add-form" action="http://www.biblio.com/cart.php" method="get"><input type="hidden" name="bid" value="433842671"/><input type="hidden" name="add" value="1"/><input type="hidden" name="int" value="keyword_search"/><button onclick="_gaq.push(['_trackEvent', 'cart_search_add', 'relevance', '11']);" type="submit" value="Add to cart" class="btn btn-large brown-btn col-md-12 col-xs-12 cart-btn"/><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"/> Add to cart</form> 
## 
## [[12]]
## <form class="ob-add-form" action="http://www.biblio.com/cart.php" method="get"><input type="hidden" name="bid" value="724847209"/><input type="hidden" name="add" value="1"/><input type="hidden" name="int" value="keyword_search"/><button onclick="_gaq.push(['_trackEvent', 'cart_search_add', 'relevance', '12']);" type="submit" value="Add to cart" class="btn btn-large brown-btn col-md-12 col-xs-12 cart-btn"/><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"/> Add to cart</form> 
## 
## [[13]]
## <form class="ob-add-form" action="http://www.biblio.com/cart.php" method="get"><input type="hidden" name="bid" value="249002914"/><input type="hidden" name="add" value="1"/><input type="hidden" name="int" value="keyword_search"/><button onclick="_gaq.push(['_trackEvent', 'cart_search_add', 'relevance', '13']);" type="submit" value="Add to cart" class="btn btn-large brown-btn col-md-12 col-xs-12 cart-btn"/><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"/> Add to cart</form> 
## 
## [[14]]
## <form class="ob-add-form" action="http://www.biblio.com/cart.php" method="get"><input type="hidden" name="bid" value="405284703"/><input type="hidden" name="add" value="1"/><input type="hidden" name="int" value="keyword_search"/><button onclick="_gaq.push(['_trackEvent', 'cart_search_add', 'relevance', '14']);" type="submit" value="Add to cart" class="btn btn-large brown-btn col-md-12 col-xs-12 cart-btn"/><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"/> Add to cart</form> 
## 
## [[15]]
## <form class="ob-add-form" action="http://www.biblio.com/cart.php" method="get"><input type="hidden" name="bid" value="815848914"/><input type="hidden" name="add" value="1"/><input type="hidden" name="int" value="keyword_search"/><button onclick="_gaq.push(['_trackEvent', 'cart_search_add', 'relevance', '15']);" type="submit" value="Add to cart" class="btn btn-large brown-btn col-md-12 col-xs-12 cart-btn"/><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"/> Add to cart</form> 
## 
## [[16]]
## <form class="ob-add-form" action="http://www.biblio.com/cart.php" method="get"><input type="hidden" name="bid" value="815848891"/><input type="hidden" name="add" value="1"/><input type="hidden" name="int" value="keyword_search"/><button onclick="_gaq.push(['_trackEvent', 'cart_search_add', 'relevance', '16']);" type="submit" value="Add to cart" class="btn btn-large brown-btn col-md-12 col-xs-12 cart-btn"/><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"/> Add to cart</form> 
## 
## [[17]]
## <form class="ob-add-form" action="http://www.biblio.com/cart.php" method="get"><input type="hidden" name="bid" value="740842624"/><input type="hidden" name="add" value="1"/><input type="hidden" name="int" value="keyword_search"/><button onclick="_gaq.push(['_trackEvent', 'cart_search_add', 'relevance', '17']);" type="submit" value="Add to cart" class="btn btn-large brown-btn col-md-12 col-xs-12 cart-btn"/><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"/> Add to cart</form> 
## 
## [[18]]
## <form class="ob-add-form" action="http://www.biblio.com/cart.php" method="get"><input type="hidden" name="bid" value="534028967"/><input type="hidden" name="add" value="1"/><input type="hidden" name="int" value="keyword_search"/><button onclick="_gaq.push(['_trackEvent', 'cart_search_add', 'relevance', '18']);" type="submit" value="Add to cart" class="btn btn-large brown-btn col-md-12 col-xs-12 cart-btn"/><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"/> Add to cart</form> 
## 
## [[19]]
## <form class="ob-add-form" action="http://www.biblio.com/cart.php" method="get"><input type="hidden" name="bid" value="819945767"/><input type="hidden" name="add" value="1"/><input type="hidden" name="int" value="keyword_search"/><button onclick="_gaq.push(['_trackEvent', 'cart_search_add', 'relevance', '19']);" type="submit" value="Add to cart" class="btn btn-large brown-btn col-md-12 col-xs-12 cart-btn"/><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"/> Add to cart</form> 
## 
## [[20]]
## <form class="ob-add-form" action="http://www.biblio.com/cart.php" method="get"><input type="hidden" name="bid" value="756720938"/><input type="hidden" name="add" value="1"/><input type="hidden" name="int" value="keyword_search"/><button onclick="_gaq.push(['_trackEvent', 'cart_search_add', 'relevance', '20']);" type="submit" value="Add to cart" class="btn btn-large brown-btn col-md-12 col-xs-12 cart-btn"/><span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"/> Add to cart</form> 
## 
## attr(,"class")
## [1] "XMLNodeSet"
```

```r
# extract book ids
xpath <- "//div[@class='row-fixed'][position()<4]/form/input[@name='bid']/@value"
bids <- unlist(xpathApply(search_page, xpath, as.numeric))
bids
```

```
##  [1] 745162375 724847744 755785471 724420313 819945705 724419579 244413814
##  [8] 625362790 648849994 835578740 433842671 724847209 249002914 405284703
## [15] 815848914 815848891 740842624 534028967 819945767 756720938
```

```r
# add items to shopping cart
for(i in seq_along(bids))  {
	res <- getForm(uri = cart_url, 
	                 curl = handle, 
	                 bid = bids[i], 
	                 add = 1, 
	                 int = "keyword_search")
}

# inspect shopping cart
cart  <- htmlParse(getURL(url=cart_url, curl=handle))
clean <- function(x)  str_replace_all(xmlValue(x),"(\t)|(\n)"," ")
xpathSApply(cart, "//h3/a", clean)
```

```
##  [1] "Introduction to Statistics and Data Analysis (4th Hardcover Edition) by Jay L. Devore, Roxy Peck and Chris Olsen"         
##  [2] "Elementary Statistics with Data CD Only (Hardcover Edition) by William Navidi and Barry Monk"                             
##  [3] "Doing Data Analysis with SPSS: Version 18.0 (5th US Edition) by Robert Carver and Jane Gradwohl Nash"                     
##  [4] "Elementary Statistics: A Step by Step Approach with Data CD (8th Hardcover Edition) by Bluman"                            
##  [5] "Nonparametric Tests for Complete Data by Vilijandas Bagdonavi?us and Julius Kruopis"                                      
##  [6] "Mining Graph Data by Diane J. Cook and Lawrence B. Holder"                                                                
##  [7] "Business Data Communications and Networking by FitzGerald, Jerry"                                                         
##  [8] "The Pennsylvania-Kentucky Rifle by Kauffman, Henry J"                                                                     
##  [9] "Christ is Our Cornerstone: 100 Years at Lititz Mennonite Church by Lapp, Alice Weber"                                     
## [10] "Genealogical Data Relating to the German Settlers of Pennsylvania and Adjacent Territory by Hocker, Edward W"             
## [11] "Data Structures and Algorithms in C++ by DROZDEK ADAM"                                                                    
## [12] "Data Structures and Other Objects Using Java (4th Edition) by Main, Michael"                                              
## [13] "Mathematical Statistics and Data Analysis (with CD Data Sets) (Available 2010 Titles Enhanced Web Assign) by Rice, John A"
## [14] "Introduction to Data Mining by Tan, Pang-Ning; Steinbach, Michael; Kumar, Vipin"                                          
## [15] "Business Driven Data Communications by Gendron, Michael"                                                                  
## [16] "Data Mining Concepts and Techniques by Han, Jiawei"                                                                       
## [17] "Skillful Inquiry/Data Team by Nancy Love"                                                                                 
## [18] "Data Literacy for Teachers by Nancy Love"                                                                                 
## [19] "Stats: Data and Models by De Veaux, Richard D.; Velleman, Paul F.; Bock, David E"                                         
## [20] "Data Management: Database and Organizations by Watson, Richard T.; Bostrom, Robert P"
```

```r
# request header
cat(str_split(info$value()["headerOut"],"\r")[[1]][1:13])
```

```
## GET /search.php?keyisbn=data HTTP/1.1 
## Host: www.biblio.com 
## Accept: */* 
## from: eddie@r-datacollection.com 
## user-agent: R Under development (unstable) (2015-01-13 r67443), x86_64-apple-darwin10.8.0 
##  
## GET /cart.php?bid=745162375&add=1&int=keyword_search HTTP/1.1 
## Host: www.biblio.com 
## Accept: */* 
## Cookie: variation=res_a; vis=language%3Ach%7Ccountry%3A8%7Ccurrency%3A9%7Cvisitor%3A77IJAi78Uh35qJkO8jZ5o9JvOZi6hXY6JhzbBJYPwHrLno9mcl0eWHzCZly7w00117490014399964271306013052%7Cver%3A4 
## from: eddie@r-datacollection.com 
## user-agent: R Under development (unstable) (2015-01-13 r67443), x86_64-apple-darwin10.8.0
```

```r
# response header
cat(str_split(info$value()["headerIn"],"\r")[[1]][1:14])
```

```
## HTTP/1.1 200 OK 
## Server: nginx 
## Date: Wed, 19 Aug 2015 15:00:27 GMT 
## Content-Type: text/html; charset=UTF-8 
## Content-Length: 107019 
## Connection: keep-alive 
## Keep-Alive: timeout=60 
## Set-Cookie: vis=language%3Ach%7Ccountry%3A8%7Ccurrency%3A9%7Cvisitor%3A77IJAi78Uh35qJkO8jZ5o9JvOZi6hXY6JhzbBJYPwHrLno9mcl0eWHzCZly7w00117490014399964271306013052%7Cver%3A4; expires=Mon, 17-Aug-2020 15:00:27 GMT; path=/; domain=.biblio.com; httponly 
## Set-Cookie: variation=res_a; expires=Thu, 20-Aug-2015 15:00:27 GMT; path=/; domain=.biblio.com; httponly 
## X-Mod-Pagespeed: 1.9.32.3-4448 
## Access-Control-Allow-Credentials: true 
## Vary: User-Agent,Accept-Encoding 
## Expires: Thu, 20 Aug 2015 15:00:27 GMT 
## Cache-Control: max-age=86400
```

# page 254

*Reported by: Laurent Franckx (2015-06-10)*

There has been a change to the install_github function of the devtools package. To install Rwebdriver use:


```r
library(devtools)

install_github("crubba/Rwebdriver")
```


# page 299--310

The website holding the UK government press releases has been altered slightly. To get the date and organisation you need to change the XPaths here...


```r
library(XML)

organisation <- xpathSApply(tmp, "//dl[@data-trackposition='top']//a[@class='organisation-link']", xmlValue)
publication <- xpathSApply(tmp, "//dl[@class='primary-metadata']//abbr[@class='date']", xmlValue)
```

... and here...


```r
for(i in 2:length(list.files("Press_Releases/"))){
    tmp <- readLines(str_c("Press_Releases/", i, ".html"))
    tmp <- str_c(tmp, collapse = "")
    tmp <- htmlParse(tmp)
    release <- xpathSApply(tmp, "//div[@class='block-4']", xmlValue)
    organisation <- xpathSApply(tmp, "//dl[@data-trackposition='top']//a[@class='organisation-link']", xmlValue)
    publication <- xpathSApply(tmp, "//dl[@class='primary-metadata']//abbr[@class='date']", xmlValue)
    if(length(release) != 0){
        n <- n + 1
        tmp_corpus <- Corpus(VectorSource(release))
        release_corpus <- c(release_corpus, tmp_corpus)
        meta(release_corpus[[n]], "organisation") <- organisation[1]
        meta(release_corpus[[n]], "publication") <- publication
    }
}
```

The prescindMeta() function is defunct as of version 0.6 of the tm package. The meta data can now be gathered with the meta() function.


```r
meta_organisation <- meta(release_corpus, type = "local", tag = "organisation")
meta_publication <- meta(release_corpus, type = "local", tag = "publication")

meta_data <- data.frame(
    organisation = unlist(meta_organisation), 
    publication = unlist(meta_publication)
)
```

The sFilter() function is also defunct. You can filter the corpus using meta().


```r
release_corpus <- release_corpus[
    meta(release_corpus, tag = "organisation") == "Department for Business, Innovation & Skills" |
    meta(release_corpus, tag = "organisation") == "Department for Communities and Local Government" |
    meta(release_corpus, tag = "organisation") == "Department for Environment, Food & Rural Affairs" |
    meta(release_corpus, tag = "organisation") == "Foreign & Commonwealth Office" |
    meta(release_corpus, tag = "organisation") == "Ministry of Defence" |
    meta(release_corpus, tag = "organisation") == "Wales Office"        
]
```

The *stringr* package also produces a hick-up with the updated version of the tm package, thus we switch to base R.


```r
tm_filter(release_corpus, FUN = function(x) any(grep("Afghanistan", content(x))))
```

We need to wrap the replace function with the new content_transformer()...


```r
release_corpus <- tm_map(
    release_corpus, 
    content_transformer(
        function(x, pattern){
            gsub(
                pattern = "[[:punct:]]", 
                replacement = " ",
                x
            )
        )
    )
)
```

Moreover, the tolower() function needs to be wrapped with the content_transformer()...


```r
release_corpus <- tm_map(release_corpus, content_transformer(tolower))
```

The prescindMeta() function is also defunct on page 310...


```r
org_labels <- unlist(meta(release_corpus, "organisation"))
```

# page 315

Since the sFilter() and prescindMeta() functions are defunct as of version 0.6 of the tm package, you need to change the code on page 315 to filter the corpus.


```r
short_corpus <- release_corpus[c(
    which(
        meta(
            release_corpus, tag = "organisation"
        ) == "Department for Business, Innovation & Skills"
    )[1:20],
    which(
        meta(
            release_corpus, tag = "organisation"
        ) == "Wales Office"
    )[1:20],
    which(
        meta(
            release_corpus, tag = "organisation"
        ) == "Department for Environment, Food & Rural Affairs"
    )[1:20]
)]

table(unlist(meta(short_corpus, "organisation")))
```




# page 243 / 9.1.5.3

*reported by Jane Yu*

The installation of RHTMLForms package would fail - it's omgehat.net not omegahat.org. Use this instead ...


```r
install.packages("RHTMLForms", repos = "http://www.omegahat.net/R", type="source")
```






