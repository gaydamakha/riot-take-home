# Riot Takehome Task

The specification can be found here [specification.md](specification.md)

## Launching

To launch the project, you need to have `docker` and `docker-compose` installed on your machine.

To use the default environment variables, you can simply run the following command:
```shell
./scripts/launch.sh
```

If you want to use a custom environment, you can copy the `.env.srv` file to `.env` and edit it to your liking.
```shell
## Generate the swagger documentation
sh scripts/swagger.sh
cp .env.srv .env
## Edit the .env file to your liking, and then launch the project
docker-compose up -d --build
```

The project is then available on [http://localhost:9999](`http://localhost:9999`)

## OpenAPI Documentation

The swagger documentation is available on [http://localhost:9999/doc/swagger.yaml](`http://localhost:9999/doc/swagger.yaml`).
