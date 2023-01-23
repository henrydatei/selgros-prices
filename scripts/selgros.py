import requests
from requests.structures import CaseInsensitiveDict
import json
import re
from bs4 import BeautifulSoup

baseurl = "https://www.mydoamin.com/selgros-prices"

headers = CaseInsensitiveDict()
headers["Connection"] = "keep-alive"
headers["Pragma"] = "no-cache"
headers["Cache-Control"] = "no-cache"
headers["sec-ch-ua-mobile"] = "?0"
headers["User-Agent"] = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/92.0.4515.107 Safari/537.36"
headers["Accept"] = "*/*"
headers["Sec-Fetch-Site"] = "same-origin"
headers["Sec-Fetch-Mode"] = "cors"
headers["Sec-Fetch-Dest"] = "empty"
headers["Referer"] = "https://artikel.selgros.de/"
headers["Accept-Language"] = "de-DE,de;q=0.9,en-US;q=0.8,en;q=0.7"

def checkPriceForProductViaWebsite(selgrosID):
    r = requests.get('https://artikel.selgros.de/artikel/{}'.format(selgrosID), headers = headers)
    soup = BeautifulSoup(r.text, "html.parser")
    script_tag = soup.find("script", {"id": "__NEXT_DATA__"})
    json_blob = json.loads(script_tag.get_text())
    return json_blob

def checkPriceForProduct(selgrosID):
    params = {"code": selgrosID}
    try:
        r = requests.get("https://artikel.selgros.de/_next/data/JcyxwcubGSzYhTLk-_Ub1/de/artikel/{}.json".format(selgrosID), params = params, headers = headers)
        r.raise_for_status()
        data = r.json()["pageProps"]["product"]
    except:
        try:
            data = checkPriceForProductViaWebsite(selgrosID)["props"]["pageProps"]["product"]
        except:
            return None
    grossPrice = data["price"]["grossPrice"]
    try:
        offerGrossPrice = data["offerPrice"]["grossPrice"]
        params2 = {"selgrosID": selgrosID, "price": grossPrice, "offerprice": offerGrossPrice}
    except:
        params2 = {"selgrosID": selgrosID, "price": grossPrice}
    r2 = requests.get(baseurl + "/api/addPrice.php", params = params2)
    r2.raise_for_status()

r = requests.get(baseurl + "/api/getProducts.php")
r.raise_for_status()
for selgrosID in r.json():
    checkPriceForProduct(selgrosID)
