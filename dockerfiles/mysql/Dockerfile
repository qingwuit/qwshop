# update from laradock/laradock 

#################################
# Mysql
#################################

FROM mysql:5.7.36

LABEL maintainer="qingwuit <bishashiwo@gmail.com>"

#####################################
# Set Timezone
#####################################

RUN ln -snf /usr/share/zoneinfo/PRC /etc/localtime && echo PRC > /etc/timezone && chown -R mysql:root /var/lib/mysql/

COPY ./my.cnf /etc/mysql/conf.d/my.cnf
COPY ./docker-entrypoint-initdb.d /docker-entrypoint-initdb.d

RUN rm -rf /etc/mysql/data \
    && chmod 0444 /etc/mysql/conf.d/my.cnf \
    && mkdir -p /etc/mysql/custum/data/

CMD ["mysqld"]

EXPOSE 3306