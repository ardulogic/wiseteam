# Preparation
* PHP Version `^8.3.0`
* Enable `sodium` extension
* Make sure `openssl` is available and `OPENSSL_CONF` environment variable is set
to your `openssl.conf` file

# Backend installation
* `scoop install symfony-cli`
* `composer install`
* Create `.env.local` if you're setting up local environment


### Database Configuration
* Create a new schema / user in your database
#### For Production
* Set the `DATABASE_URL` environment variable in your deployment system
  (e.g., Docker, server config, or platform settings)
#### For Local Testing
* Create `.env.local`
* Add `DATABASE_URL=(Based on your setup)`
* Example:
  * `DATABASE_URL="mysql://username:password@127.0.0.1:3306/schemaName?serverVersion=8.2.0&charset=utf8mb4"`
#### Run migrations when ready:
* `php bin/console doctrine:migrations:migrate`

### Authentication (JWT) Configuration
* Set `JWT_PASSPHRASE` in environment or `.env.local`
  * Example: `JWT_PASSPHRASE=mL4T1zN9hs7L1k6cLOZt0vN1L9lZBtB9F3JxNvRgkDc=`
* `php bin/console lexik:jwt:generate-keypair`


# Frontend Installation:
* Make sure node version is at least v16.9
* `cd frontend`
* `npm install`
* `npm run build`
* Or for development:
* `npm run build:dev`

# Launch
* `symfony serve`

# Thoughts
### Why did you choose Vue framework?
Let's assume we will be expanding this project:
* Vanilla javascript can get cumbersome quite fast, jQuery is outdated
* We can maintain clear project structure, hire frontend devs separately
* We can reuse components easily in other projects too
* Current Google crawlers are smart enough to understand SPA pages, so server-side rendering is no longer required

### Why didn't you use twig templates?
* Extra strain for the server
* Since we're making a single-page crud, we need a token based authentication anyway
  * Yes, we could use session cookie, but I just don't like it. In my experience you face problems as the project grows.
  * If we have token-based auth, we can also make `Android/iOS` app integrations without having to recode authentication
* Overall it's better not to mix backend / frontend
* We can build a more complex UI with VueJS

### What are some of the nice things here?
* Frontend has scripts that build and move files to symfony `public` directory automatically
* Symfony's debugbar is retained by serving vue-generated `spa.html` via a simple controller that injects it in dev environments.
* Table columns are indexed
* Validation is done both on DTO and Entity cleanly, no macaroni
* Lazy-loaded js and css
* Properly validated ISBN with a checksum

### What would you improve in backend?
* `ElasticSearch` for better search performance
* `Refresh token`. Could do it, didn't have time for it.
  * Current token expires every hour, you need to login again
* `API rate limiter`. For better security, we should limit the calls
* `Dockerfile` for faster deployment
### What would you improve in frontend?
* I don't really like the vue `naiveUI` library, even though it's very popular:
  * It injects a lot of *inline style* to the elements
  * It was *faster to just use it*
  * I still made some components of my own for cleaner code
* Display logged-in user picture / name