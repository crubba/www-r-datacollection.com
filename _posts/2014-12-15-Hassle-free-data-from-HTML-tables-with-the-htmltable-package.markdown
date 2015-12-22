---
layout: blog-layout
title: "Hassle-free data from HTML tables with the htmltable package"
author: "Christian Rubba"
authorref: "https://twitter.com/christianrubba"
date: 2014-12-15 17:50:20
teaser-pic: "/img/blog/carpenter.png"
category: rstats
blogo: "main"
---

 <font color="red"><b>[2015-01-15 The syntax in this article is outdated. For a revised version take a look at the [package vignette](http://cran.r-project.org/web/packages/htmltab/vignettes/htmltab.html)]</b></font><br>
HTML tables are a standard way to display tabular information online. Getting HTML table data into R is fairly straightforward with the `readHTMLTable()` function of the *XML* package. But tables on the web are primarily designed for displaying and consuming data, not for analytical purposes. Peculiar design choices for HTML tables are therefore frequently made which tend to produce useless outputs when run through `readHTMLTable()`. I found that sometimes these outputs could be saved with a little bit of (tedious) post-processing, but just as often they could not. To make working with HTML tables easier and less time-consuming, I developed *htmltable*, a package for the R system that tries to alleviate these problems directly in the parsing stage when the structural information is still available. Its main advantages over `readHTMLTable()` are twofold:

- Consideration of row and column spans in the HTML table body and header cells
- More control over the process that translates HTML cells into R table cells

This blog post discusses the application of *htmltable* for two use cases where the package provides a significant improvement over `readHTMLTable()`. 

(I make use of the R packages *magrittr*, *tidyr* and *stringr* to process table outputs. Neither of the three is required for running *htmltable*.)

## Installation
At the moment, the package is only available from my GitHub repository. You can install the package using the *devtools* package:

```r
devtools::install_github("crubba/htmltable")
library(htmltable)
```

## How to read HTML tables with htmltable()
The principal function of *htmltable* is (surprise!) `htmltable()`. The behaviour of `htmltable()` is modeled closely after `readHTMLtable()`, and many argument names are identical. Any function call requires passing a value to its _doc_ argument. This value may be of two kinds:

1. a URL or file path for the HTML document where the table lives
2. a parsed HTML object of the entire page

Both methods return a single R table object. Unlike `readHTMLTable()`, `htmltable()` requires users to be specific about the table they would like to have returned. This is done via the _which_ argument. This may be either a numeric value for the table's position in the page, or a character value that describes an XPath statement.


## 1. Corrections for rowspans and colspans by default
In many HTML tables, spans are used to allow cell values to extend across multiple cells. `htmltable()` recognizes spans and expands tables automatically. To illustrate this feature, take a look at the HTML table in the Language section of this [Wikipedia page about Demography in the UK](http://en.wikipedia.org/wiki/Demography_of_the_United_Kingdom#Languages). The header information spans across three consecutive rows. To get the table into R, we have to pass an identifiying information to the _which_ argument. I use an XPath statement that I wrote while exploring the HTML page with Web Developer Tools. One that works is "//th[text() = 'Ability']/ancestor::table":


```r
url <- "http://en.wikipedia.org/wiki/Demography_of_the_United_Kingdom"
ukLang <- htmltable(doc = url, which = "//th[text() = 'Ability']/ancestor::table")
head(ukLang)
```

```
##                                         Ability Wales >> Welsh >> Number
## 1 Understands but does not speak, read or write                  157,792
## 2                      Speaks, reads and writes                  430,717
## 3             Speaks but does not read or write                   80,429
## 4           Speaks and reads but does not write                   45,524
## 5             Reads but does not speak or write                   44,327
## 6                   Other combination of skills                   40,692
##   Wales >> Welsh >> % Scotland >> Scottish Gaelic >> Number
## 1               5.15%                                23,357
## 2              14.06%                                32,191
## 3               2.63%                                18,966
## 4               1.49%                                 6,218
## 5               1.45%                                 4,646
## 6               1.33%                                 1,678
##   Scotland >> Scottish Gaelic >> % Scotland >> Scots >> Number
## 1                            0.46%                     267,412
## 2                            0.63%                   1,225,622
## 3                            0.37%                     179,295
## 4                            0.12%                     132,709
## 5                            0.09%                     107,025
## 6                            0.03%                      17,381
##   Scotland >> Scots >> % Northern Ireland >> Irish >> Number
## 1                  5.22%                              70,501
## 2                 23.95%                              71,996
## 3                  3.50%                              24,677
## 4                  2.59%                               7,414
## 5                  2.09%                               5,659
## 6                  0.34%                               4,651
##   Northern Ireland >> Irish >> %
## 1                          4.06%
## 2                          4.15%
## 3                          1.42%
## 4                          0.43%
## 5                          0.33%
## 6                          0.27%
##   Northern Ireland >> Ulster-Scots >> Number
## 1                                     92,040
## 2                                     17,228
## 3                                     10,265
## 4                                      7,801
## 5                                     11,911
## 6                                        959
##   Northern Ireland >> Ulster-Scots >> %
## 1                                 5.30%
## 2                                 0.99%
## 3                                 0.59%
## 4                                 0.45%
## 5                                 0.69%
## 6                                 0.06%
```

The header information has been recast into a format that respects the hierarchical order of the variables and yet only spans a single line in the R table. If you prefer a different seperator between variables, pass it to the _headerSep_ argument. This format was chosen to make further processing of the table easy. For example, using functionality from the *tidyr* package, the next couple of data cleaning steps may be the following:


```r
library(tidyr)
library(magrittr)

ukLang %<>% gather(key, value, -Ability)
```

This statement restructures the variables in a more useful long format. From this we can separate the variables using an appropriate regular expression such as " >> ". 


```r
ukLang %>% separate(key, into = c("region", "language", "statistic"), sep = " >> ") %>% head
```

```
##                                         Ability region language statistic
## 1 Understands but does not speak, read or write  Wales    Welsh    Number
## 2                      Speaks, reads and writes  Wales    Welsh    Number
## 3             Speaks but does not read or write  Wales    Welsh    Number
## 4           Speaks and reads but does not write  Wales    Welsh    Number
## 5             Reads but does not speak or write  Wales    Welsh    Number
## 6                   Other combination of skills  Wales    Welsh    Number
##     value
## 1 157,792
## 2 430,717
## 3  80,429
## 4  45,524
## 5  44,327
## 6  40,692
```

`htmltable()` also automatically expands row and column spans when they appear in the table's body. 

## 2. More control over cell value conversion
`htmltable()` offers you more control over what part of the HTML table is used in the R table. You can exert this control via `htmltables()`'s _body_, _header_, _bodyFun_, _headerFun_, _rm&#95;escape_, _rm&#95;footnote_ and _rm&#95;superscript_ arguments. 

### _body_ and _header_ arguments
It is not possible for `htmltable()` to correctly identify header and body elements in all the tables. Although there is a semantically *correct* way to organize header and body elements in HTML tables, web designers do not necessarily need to adhere to them to produce visually appealing tables. The *htmltable* package employs reasonable heuristics for identification but they are no guarantee. If you find that the table is not correctly assembled, you can try to give the function more information through its _header_ and _body_ arguments. These arguments are used to pass information about which rows should be used for the contruction of the header and the body. Both accept numeric values for the rows, but a more robust way is to use an XPath that identifies the respective rows. To illustrate, take a look at this [Wikipedia page about the New Zealand General Election in 2002](http://en.wikipedia.org/wiki/New_Zealand_general_election,_2002#Electorate_results). The table uses cells that spann the entire column range to classify General and Maori electorates (yellow background). We need to control for this problem explicitly in the assembling stage. I pass the XPath "tr[./td[not(@colspan = '10')]]" to the _body_ argument to explicitly discard all rows from the body that have a \<td\> cell with a colspan attribute of 10:


```r
url <- "http://en.wikipedia.org/wiki/New_Zealand_general_election,_2002"
xp <- "//caption[starts-with(text(), 'Electorate results')]/ancestor::table"
nz1 <- htmltable(doc = url, which = xp, body = "//tr[./td[not(@colspan = '10')]]")
head(nz1)
```

```
##             Electorate Incumbent      Incumbent         Winner
## 1               Aoraki               Jim Sutton     Jim Sutton
## 2     Auckland Central            Judith Tizard  Judith Tizard
## 3      Banks Peninsula               Ruth Dyson     Ruth Dyson
## 4        Bay of Plenty               Tony Ryall     Tony Ryall
## 5 Christchurch Central              Tim Barnett    Tim Barnett
## 6    Christchurch East           Lianne Dalziel Lianne Dalziel
##           Winner Majority                   Runner up
## 1     Jim Sutton          Wayne F Marriott (National)
## 2  Judith Tizard                Pansy Wong (National)
## 3     Ruth Dyson              David Carter (National)
## 4     Tony Ryall               Peter Brown (NZ First)
## 5    Tim Barnett              Nicky Wagner (National)
## 6 Lianne Dalziel          Stephen Johnston (National)
##                     Runner up                  Third place
## 1 Wayne F Marriott (National) Tony Bunting (United Future)
## 2       Pansy Wong (National)       Nandor Tanczos (Green)
## 3     David Carter (National)           Rod Donald (Green)
## 4      Peter Brown (NZ First)           Mei Taare (Labour)
## 5     Nicky Wagner (National)          Matt Morris (Green)
## 6 Stephen Johnston (National)        Mary McCammon (Green)
##                    Third place
## 1 Tony Bunting (United Future)
## 2       Nandor Tanczos (Green)
## 3           Rod Donald (Green)
## 4           Mei Taare (Labour)
## 5          Matt Morris (Green)
## 6        Mary McCammon (Green)
```

You might object that ideally these rows should not be discarded, but used for what they are -- variable/header information! The capability to process in-table variables is an issue that I leave for future versions of the package.

### Removal of unneeded information
Many HTML tables include additional information which are of little interest to data analysts such as information encoded in superscript and footnote tags, as well as escape sequences. By default, `htmltable()` removes information from the first two and replaces all escape sequences by a whitespace. You can change this behaviour through the _rm\_superscript_, _rm\_footnotes_ and _rm\_escape_ arguments. 

## Conclusion & Current State of the package
HTML tables are a valuable data source but they frequently violate basic principles of data well-formedness. This is usually for good reason since their primary purpose is to increase readability of tabular information. *htmltable*'s goal is to reduce the need for users to interfere when working with HTML tables by relying on available structural information as well as making some assumptions about the table's design. However, you are free to exert more control over the transformation by specifying various function arguments. 

The package is still in an early development stage and it might fail for tables for which it should not. At this point, I would very much appreciate your [feeback](https://github.com/crubba/htmltable/issues) on the package and hear about any kind of problems you experience.
