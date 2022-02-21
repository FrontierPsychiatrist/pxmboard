# PXM Board modernization

# Getting started

Run `./start.sh`, then go to `http://localhost:5000/install/install.php`. Choose `MySQL`
and set the following parameters:

```
HOST = mysql
USER = pxmboard
PASSWORD = password
DATABASE = pxmboard
```

When running from somewhere else, make sure to set the `DM_UID` and `DM_GID` environment variables
for docker-compose to the host user and group id. This ensures during development both the running
Docker container and the developer can write.
