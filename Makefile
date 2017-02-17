.DEFAULT_GOAL := help

build: ## bouild image
	docker-compose build

up: ## up docker containers
	docker-compose up -d
	docker-compose ps

down: ## Stops and removes the docker containers
	docker-compose down

restart: ## Restart all containers
	docker-compose restart
	docker-compose ps

ssh: ## Connect to container
	docker exec -it workers bash

loadImage: ## Load the urbania_web docker image
	docker load -i docker_images/urbania_web

getData: ## Get data from new mongo engine
	docker exec -it workers php worker.php GetData

compareData: ## Compare data
	docker exec -it workers php worker.php CompareData

deleteData: ## Compare data
	docker exec -it workers php worker.php DeleteData

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-16s\033[0m %s\n", $$1, $$2}'