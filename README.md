# Find Twitter ID
Get the ID using twitter username.

Development instance
- php 8.0
- Composer 2.1.14

Installation
```
composer install
```

Set environment variables in `.env` or copy `.env.example` to `.env`
```
TWITTER_CONSUMER_KEY=
TWITTER_CONSUMER_SECRET=
LIMIT_RATES=400
TIME_RESET=900
```
`LIMIT_RATES`: Limit of requests that can be made in the time range established in `TIME_RESET`.
`TIME_RESET`: Time range in seconds to reset the limit of requests

It is recommended not to exceed the default limits, they are the limits established by the twitter api. (https://developer.twitter.com/en/docs/twitter-api/v1/rate-limits -> `users / show`)
