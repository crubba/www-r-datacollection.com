httpDate <- function(time="now", origin="1970-01-01", type="rfc1123"){
    if(time=="now") {
        tmp <- as.POSIXlt(Sys.time(), tz="GMT")
    }else{
        tmp <- as.POSIXlt(as.POSIXct(time, origin=origin), tz="GMT")
    }
    nday    <- c("Sun", "Mon" , "Tue" , 
                 "Wed", "Thu" , "Fri" , 
                 "Sat")[tmp$wday+1]
    month   <- tmp$mon+1
    nmonth  <- c("Jan" , "Feb" , "Mar" , 
                "Apr", "May" , "Jun" , 
                "Jul" , "Aug", "Sep" , 
                "Oct" , "Nov" , "Dec")[month]
    mday <- formatC(tmp$mday, width=2, flag="0")
    hour <- formatC(tmp$hour, width=2, flag="0")
    min  <- formatC(tmp$min , width=2, flag="0")
    sec  <- formatC(round(tmp$sec) , width=2, flag="0")
    if(type=="rfc1123"){
        return(paste0(nday, ", ", 
                      mday," ", nmonth, " ", tmp$year+1900, " ", 
                      hour, ":", min, ":", sec, " GMT") )
    }else{
      stop("Not implemented")
    }
}

file.date <- function(filename, timezone=Sys.timezone() ) {
    as.POSIXlt( min(unlist( file.info(filename)[4:6] )),
                origin = "1970-01-01", 
                tz = timezone)
}

# usage: 
# httpDate()
# httpDate( file.date("WorstFilms.Rdata") ) 
# httpDate("2001-01-02")
# httpDate("2001-01-02 18:00")
# httpDate("2001-01-02 18:00:01")
# httpDate(60*60*24*30.43827161*12*54+60*60*24*32)
# httpDate(-10*24*60*60,origin="2014-02-01")



