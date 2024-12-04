build:
	cd .\docker\ && docker-compose up -d --build

close:
	cd .\docker\ && docker-compose down

cb:
	make close && make build