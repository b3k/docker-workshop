# docker-workshop


## What is Docker?

> an open-source project that automates the deployment of software applications inside containers by providing an additional layer of abstraction and automation of OS-level virtualization on Linux.


Docker is a tool that allows developers, sys-admins etc. to easily deploy their applications in a sandbox (called containers) to run on the host operating system i.e. Linux. The key benefit of Docker is that it allows users to package an application with all of its dependencies into a standardized unit for software development. Unlike virtual machines, containers do not have high overhead and hence enable more efficient usage of the underlying system and resources.


## What are containers?

By leveraging the low-level mechanics of the host operating system, containers provide most of the isolation of virtual machines at a fraction of the computing power.

## Getting started

`docker run hello-world`


`docker pull busybox`


`docker images | grep busybox`


`docker run busybox`

`docker run busybox echo "hello from busybox"`

`docker ps`

`docker ps -a`

`docker run -it busybox sh`


INSIDE CONTAINER:

`rm -rf bin`


`docker rm`


`docker rm $(docker ps -a -q -f status=exited)`

`docker rmi`


### Terminology:

- **Images** - The blueprints of our application which form the basis of containers. In the demo above, we used the docker pull command to download the busybox image.
- **Containers** - Created from Docker images and run the actual application. We create a container using docker run which we did using the busybox image that we downloaded. A list of running containers can be seen using the docker ps command.
- **Docker Daemon** - The background service running on the host that manages building, running and distributing Docker containers. The daemon is the process that runs in the operating system which clients talk to.
- **Docker Client** - The command line tool that allows the user to interact with the daemon. More generally, there can be other forms of clients too - such as Kitematic which provide a GUI to the users.
- **Docker Hub** - A registry of Docker images. You can think of the registry as a directory of all available Docker images. If required, one can host their own Docker registries and can use them for pulling images.


## Static site

`docker run --rm prakhar1989/static-site`


`docker run -d -P --name static-site prakhar1989/static-site`


`docker port static-site`

If docker toolbox:
`docker-machine ip default`


`docker run -p 8888:80 prakhar1989/static-site`

`docker stop static-site`

## Images

The TAG refers to a particular snapshot of the image and the IMAGE ID is the corresponding unique identifier for that image.

`docker pull ubuntu:18.04`

`docker search ubuntu`

## The Dockerfile

`docker build -f Dockerfile-php -t gamivo/php-simple php-context-1`

`docker run -p 8888:80 gamivo/php-simple`

chrome http://localhost:8888


`docker image inspect gamivo/php-simple:latest`


## Docker Network

`docker ps | grep php-simple`

`0.0.0.0:8888->80/tcp`

`docker network ls`

`docker network inspect bridge`

`docker run --name hebel -d -p 8888:80 gamivo/php-simple:latest`

`docker exec -it hebel bash`

`curl 172.17.0.1:8888`

`docker stop hebel`

---

`docker rm hebel`

`docker network create workshop`

`docker run --net workshop --name hebel -d gamivo/php-simple:latest`

`docker run --net workshop --name hebel2 -d gamivo/php-simple:latest`

`docker exec -it hebel bash`

`curl hebel2`

`exit`

`docker exec -it hebel2 bash`

`curl hebel`

## Docker-compose

> Compose is a tool that is used for defining and running multi-container Docker apps in an easy way. It provides a configuration file called docker-compose.yml that can be used to bring up an application and the suite of services it depends on with just one command. Compose works in all environments: production, staging, development, testing, as well as CI workflows, although Compose is ideal for development and testing environments.


Stop all docker containers

`docker-compose up`

http://localhost:8888

`docker-compose down`

`docker-compose up -d`

http://localhost:8888/images/cat.png


