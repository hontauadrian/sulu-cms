# Sulu-Docker

**Development environment** for the [Sulu](https://sulu.io/) content management platform built with
[Docker Compose](https://docs.docker.com/compose/).

> If you are experiencing bad performance on Mac, we recommend to
> use [docker virtiofs](https://www.docker.com/blog/speed-boost-achievement-unlocked-on-docker-desktop-4-6-for-mac/).

## URLs

* Sulu-Website: `PROJECT_DOMAIN:PORT_NGINX` (default: `sulu.localhost:8080`)
* Sulu-Admin: `PROJECT_DOMAIN:PORT_NGINX/admin` (default: `sulu.localhost:8080/admin`)
* MySQL: `PROJECT_DOMAIN:PORT_MYSQL` (default: `sulu.localhost:3306`)

## Install Environment

```bash
make install
```

If you want to change the env, you can change it in the makefile install command. Default is set to dev.

The `.env` file contains several environment variables that are used to throughout the environment.
This allows to configure the project path, database settings, public ports of the services and the domain name.

To access your project via the configured domain, you need to add it to your `/etc/hosts` file:

```
127.0.0.1    sulu.localhost (value of your PROJECT_DOMAIN)
```

After completing these steps the services are accessible via the URLs listed above.

After the first installation you can start or stop the containers using these commands:

```bash
make up
make down
```

## Building Front-end

Run this command for building the administration interface frontend application

```bash
make admin-update-build
```

To start watcher when working on FE run this command. Any changes made that are related to FE will be automatically
build

```bash
make build-watch
```

## Useful Sulu commands

### Create new role
```bash
./bin/adminconsole sulu:security:role:create 
```
### Create new user
```bash
./bin/adminconsole sulu:security:user:create 
```