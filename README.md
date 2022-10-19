# Shopware Versions
This tool allows you to get the install and update release url for all Shopware versions listed on Shopwares changelog page.

## Why is this useful?
A typical Shopware download url looks like this: `https://www.shopware.com/de/Download/redirect/version/sw6/file/install_v6.4.16.0_ccfc52c31c489bed8041a13e5725183575f0593b.zip`.
The last part of the url is a md5 hash of the file. This hash changes with every update. So if you want to download a specific version of Shopware you have to know the hash.

This tool allows you to get the correct download url for a specific version.

## Open Source and free-to-use webservice
You can either use the webservice directly or use the source code to host your own version of the webservice.
You can access the webservice via `https://sw-version.dambacher.net`

## Usage
### For a specific version
Get the download url for a specific version (e.g. `6.4.16.0`):

```curl
curl -s https://sw-version.dambacher.net/version/6.4.16.0
```

The webservice will respond with a 302 redirect to the real Shopware download url.

### For the latest version
Get the download url for the latest version:

```curl
curl -s https://sw-version.dambacher.net/version
```
or 
```curl
curl -s https://sw-version.dambacher.net/version/6
```

### For partial version strings
Get the download url for the latest version of a partial version string (e.g. use `6.4.16` to get the latest patch version of `6.4.16`).

If `6.4.16.3` is the last patch version in this minor version, this will give you the download url for Shopware 6.4.16.3:

```curl
curl -s https://sw-version.dambacher.net/version/6.4.16
```

You can also request the latest version of a major version (e.g. `6.4`):

```curl
curl -s https://sw-version.dambacher.net/version/6.4
```


## Future ideas
Here's a list of ideas for future releases:
- Cache the changelog page to reduce the load on Shopware's servers and maintain a download link even if the changelog page is not accessible
- Listen for the `Accept` header and return the download url as plain text, json or xml
- Collect more data from the changelog page (e.g. hash, release date, bugfixes etc.)
- Enable to use this package via composer to get the download url in your own project without rely on the webservice