.PHONY: init-docker
init-docker:
	docker pull mysql:8.0.21

.PHONY: start-database
start-database:
	docker run --name vinproject-mysql -e MYSQL_ROOT_PASSWORD=root -e MYSQL_DATABASE=vinproject -p 3306:3306 -d mysql:8.0.21

.PHONY: init-database
init-database:
	php bin/console doctrine:migrations:migrate

.PHONY: stop-database
stop-database:
	docker stop vinproject-mysql
	docker rm vinproject-mysql
