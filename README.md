# Outbeat
heartbeat to simple php for remote monitoring of secured network uptime

## purpose
allow a secure network without any inbound traffic allowed, to poll this php file and mark its status so it can be monitored from another public or private system (eg. Zabbix Web Monitoring)

## Usage
target network to call (eg curl) `https://hostname.tld/outbeat.php?token=UPDATETOKENHERE`
monitoring network to call `https://hostname.tld/outbeat.php?token=READTOKENHERE`

## How
- target calls the URL with update token
- php writes a stamp.json file with current unix timestamp to key 'last'
- php response with json file showing last time, new time, difference of times, timeout, if time was written, status

- monitor calls the URL with read token
- php responds with good, bad or error text for state monitoring

- monitor calls the URL with read json token
- php responds with json, same structure as update but the write status is false
- allows details to be read into scripts

## Notes
- change Tokens in code before use. publish tokens are random/unused for example only
