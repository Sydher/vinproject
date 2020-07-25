.PHONY: init-docker
init-docker:
	docker pull mysql:8.0.21
	docker pull bytemark/smtp

.PHONY: start-database
start-database:
	docker run --name vinproject-mysql -e MYSQL_ROOT_PASSWORD=root -e MYSQL_DATABASE=vinproject -p 3306:3306 -d mysql:8.0.21

.PHONY: init-database
init-database:
	php bin/console doctrine:migrations:migrate --append
	php bin/console doctrine:fixtures:load --append

.PHONY: stop-database
stop-database:
	docker stop vinproject-mysql
	docker rm vinproject-mysql

.PHONY: start-smtp
start-smtp:
	docker run --name mysmtp -p 25:25 -d bytemark/smtp
