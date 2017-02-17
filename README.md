# WORKERS


Create the container:
=====================

Restore the image with

~~~~
$ make loadImage
~~~~

Build the docker images with

~~~~
$ make build
~~~~

Start the docker container with

~~~~
$ make up
~~~~

Destroy the docker continer with

~~~~
$ make down
~~~~

Restart all containers

~~~~
$ make restart
~~~~

Connect ssh with container

~~~~
$ make ssh
~~~~


Execute tasks with:
===================

This command get data from new mongo engine, the result is saved on workers/storage directory

~~~~
$ make getData
~~~~

This command compare the data between new mongo engine and old mongo engine, the result is saved on workers/storage directory

~~~~
$ make compareData
~~~~