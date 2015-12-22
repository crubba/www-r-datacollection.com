---
layout: blog-layout
title: "How to conduct a tombola with R"
author: "Simon Munzert"
authorref: "https://twitter.com/simonsaysnothin"
date: 2014-12-21 13:50:00
teaser-pic: "/img/blog/tombola/roulette.jpg"
category: rstats
blogo: "main"
---

Two weeks ago, we [announced](http://www.r-datacollection.com/blog/The-ADCR-tombola/) to raffle off three hardcover versions of our [ADCR book](http://www.r-datacollection.com/) among all followers of our Twitter account [@RDataCollection](https://twitter.com/RDataCollection). Tomorrow is closing day, so it is high time to present the drawing procedure, which, as a matter of course, is conducted with R.

## Connecting with Twitter

We start with installing the latest version of Jeff Gentry's *twitteR* package from GitHub, which makes the OAuth authentication handshake procedure very comfortable:

```r
devtools::install_github("geoffjentry/twitteR")
library(twitteR)
```

Next, we have to authenticate our Twitter app. Note that this requires that we already registered our app (for free) at [https://dev.twitter.com/](https://dev.twitter.com/) and stored the credentials (API key and secret as well as access token and secret) locally. An excellent way of storing your credentials locally as R environment variables has been described by [@JennyBryan](https://twitter.com/JennyBryan) at [http://stat545-ubc.github.io/bit003_api-key-env-var.html](http://stat545-ubc.github.io/bit003_api-key-env-var.html), which we follow here. To do so, we first write the tokens in an R object as name-value pairs (it goes without saying that the keys presented here are fictional):


```r
credentials <- c(
  "twitter_api_key=rN3Td2zZADLWZBN9Pj7X2eBN",
  "twitter_api_secret=abcqBpUzE7BQ65QJ6BRzpUzjyaRCfwn3ndrUUcqDWfhCN7Fj",
  "twitter_access_token=9287465372-6ckQsXGP83eaXCsQHFQFx5pUNhmYYqknnCwWScVk8n7L",
  "twitter_access_token_secret=ZHUxEW5fefntdyWBBB95fuXY5umZzWXdtPKtjUEP9GDcJs6w"
  )
```

In order to write the keys to a local *.Renviron* file in the default working directory, we write:

```r
fname <- paste0(normalizePath("~/"),".Renviron")
writeLines(credentials, fname)
```
We have to do this only once to retrieve the keys in later R sessions. The benefit of this approach is that we do not have to store the keys in actual R code which we plan to publish. To see if this worked, we can retrieve the file again and inspect its content:

```r
browseURL(fname)
```

After reloading R, we can use `Sys.getenv()` to retrieve the keys again and feed them to *twitteR*'s `setup_twitter_oauth()` function, which takes care of the entire handshake procedure from start to finish:

```r
api_key <- Sys.getenv("twitter_api_key")
api_secret <- Sys.getenv("twitter_api_secret")
access_token <- Sys.getenv("twitter_access_token")
access_token_secret <- Sys.getenv("twitter_access_token_secret")
setup_twitter_oauth(api_key,api_secret,access_token,access_token_secret)
```

## Retrieving the followers

Now that we have access to [@RDataCollection](https://twitter.com/RDataCollection)'s Twitter account, we collect information about the account and the number of followers:

```r
user <- getUser("RDataCollection")
user$getFollowersCount()
```
```
## 122
```

At the moment, the account has 122 followers, which gives each of them a chance of approximately 2.5 percent to win one of the books. In order to draw from the list of followers, we extract their screennames using *twitteR*'s internal methods:

```r
user_followers <- user$getFollowers()
followers_n <- length(user_followers)
followers_screennames <- vector()
for (i in 1:followers_n) {
  followers_screennames[i] <- user_followers[[i]]$screenName
}
```

To be fair, we exclude the author's accounts from the tombola:

```r
authors <- c("simonsaysnothin", "christianrubba", "marvin_dpr", "jonas_nijhuis", "phdwhipbot")
followers <- setdiff(followers_screennames, authors)
```

## Drawing the winners

Finally we are ready to draw the three winners!

```r
sample(followers, 3)
```

Well, don't worry &ndash; we won't perform the actual draw before tomorrow evening. Best of luck in winning one of the books, and a peaceful 4th Advent Sunday everyone!

## Addendum

We just drew the three winners using the closing day as seed value:

```r
set.seed(221214)
> sample(followers, 3)
```
```
## [1] "Lebowskiana" 
## [2] "restonian_va"
## [3] "SebStier"
```

The lucky winners are: [@Lebowskiana](https://twitter.com/Lebowskiana), [@restonian_va](https://twitter.com/restonian_va), and [@SebStier](https://twitter.com/SebStier). Congratulations to the three of you, and enjoy reading the book!
