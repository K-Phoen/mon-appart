yarn-install:
	docker run -it -v $(shell pwd):/usr/src/app -w /usr/src/app --rm node:10 yarn install
	docker run -it -v $(shell pwd):/usr/src/app -w /usr/src/app --rm node:10 chown -R $(shell id -u):$(shell id -g) node_modules/

yarn-dev:
	docker run -it -v $(shell pwd):/usr/src/app -w /usr/src/app --rm node:10 yarn encore dev

yarn-prod:
	docker run -it -v $(shell pwd):/usr/src/app -w /usr/src/app --rm node:10 yarn encore production
