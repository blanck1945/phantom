install: 
	cd .\src\ && composer install

up:
	cd .\docker\ && docker-compose up -d --build

down:
	cd .\docker\ && docker-compose down

rebuild:
	make close && make build

cb:
	make close && make build

exec:
	cd .\docker\ && docker-compose exec -it  sh

test:
	cd .\src\ && php ./vendor/bin/phpunit --configuration ./phpunit.xml --color
