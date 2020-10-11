# Démarrage containers Docker
.PHONY: start-docker
start-docker:
	cd docker && docker-compose up -d

# Arrêt des containers Docker
.PHONY: stop-docker
stop-docker:
	docker stop vinproject_mysql
	docker stop vinproject_maildev
	docker rm vinproject_mysql
	docker rm vinproject_maildev

# Recréer la BD
.PHONY: open-mysql
open-mysql:
	docker exec -it vinproject_mysql mysql -uroot -proot

# Initialisation et remplissage de la BD
.PHONY: init-database
init-database:
	mkdir -p public/storage/images/post
	php bin/console doctrine:migrations:migrate
	php bin/console doctrine:fixtures:load

# Lancement des TU
.PHONY: test
test:
	./bin/phpunit

# Initialisation des bundles
.PHONY: init-bundles
init-bundles:
	symfony console ckeditor:install
	symfony console assets:install public
	symfony console elfinder:install

# Nettoyage du projet
.PHONY: clean
clean:
	php bin/console cache:clear
	rm -Rf public/media/
	rm -Rf public/storage/

# Démarrage du serveur
.PHONY: start-server
start-server:
	/usr/local/bin/symfony server:start -d

# Arrêt du serveur
.PHONY: stop-server
stop-server:
	/usr/local/bin/symfony server:stop
