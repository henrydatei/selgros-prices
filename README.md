# selgeos-prices
Collect prices from Selgros, store them in a database and display price charts

Selgros is a wholesaler for gastronomy in particular (i.e. the prices shown are usually without VAT), which also has an online shop at [artikel.selgros.de](artikel.selgros.de). You can get very cheap products from Selgros, especially through discount campaigns, which is why permanent price monitoring makes sense.

This repo is divided into two parts: a part for the web server, which also hosts the database, and a part for the script, which is called regularly via cron jobs and retrieves the prices for selected products and sends them to the web server.

## Setup:
- Clone the repo and copy the `webserver` folder to your webserver
- Setup database and tables
```sql
CREATE DATABASE IF NOT EXISTS `selgros` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `selgros`;

CREATE TABLE `preise` (
  `id` int(11) NOT NULL,
  `selgrosID` int(11) NOT NULL,
  `preis` double NOT NULL,
  `angebotspreis` double DEFAULT NULL,
  `added` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `produkte` (
  `selgrosID` int(11) NOT NULL,
  `name` text NOT NULL,
  `added` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `preise`
  ADD PRIMARY KEY (`id`),
  ADD KEY `c1` (`selgrosID`);

ALTER TABLE `produkte`
  ADD PRIMARY KEY (`selgrosID`);

ALTER TABLE `preise`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `preise`
  ADD CONSTRAINT `c1` FOREIGN KEY (`selgrosID`) REFERENCES `produkte` (`selgrosID`) ON DELETE CASCADE ON UPDATE CASCADE;
```
- Set username and password for database access in `webserver/master/dbconnect.php`
- Set baseurl for your webserver in `scripts/selgros.py`