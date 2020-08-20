# Commandes relatives à docker
.PHONY: start-docker
start-docker:
	cd docker && docker-compose up -d

.PHONY: stop-docker
stop-docker:
	docker stop vinproject_mysql
	docker stop vinproject_maildev
	docker rm vinproject_mysql
	docker rm vinproject_maildev

# Commandes relatives à la base de données
.PHONY: init-database
init-database:
	php bin/console doctrine:migrations:migrate
	php bin/console doctrine:fixtures:load --append

# Commandes relatives aux tests
.PHONY: test
test:
	./bin/phpunit

# Commandes diverses
.PHONY: clean
clean:
	php bin/console cache:clear
	find public/storage/images/ -name "*.jpg" -type f -delete
