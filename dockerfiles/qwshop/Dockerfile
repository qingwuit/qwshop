# update from laradock/laradock 

#################################
# 工作台 qingwuit <bishashiwo@gmail.com>
#################################
FROM laradock/workspace:master-7.4

USER root

# node 安装
# ARG NODE_VERSION=node
# RUN mkdir -p /home/laradock/.nvm && \
#     curl -o- https://raw.githubusercontents.com/nvm-sh/nvm/v0.38.0/install.sh | bash \
#         && . /home/laradock/.nvm/nvm.sh \
#         && nvm install node \
#         && nvm use node \
#         && nvm alias node \
#         && npm config set fetch-retries 2 \
#         && npm config set fetch-retry-factor 10 \
#         && npm config set fetch-retry-mintimeout 10000 \
#         && npm config set fetch-retry-maxtimeout 60000 \
#         && npm config set registry https://registry.npm.taobao.org \
#         && ln -s `npm bin --global` /home/laradock/.node-bin 

RUN apt-get install -y nodejs && apt-get install -y npm \
        && npm config set registry https://registry.npm.taobao.org \
        && npm install -g n && n latest && node -v

# Clean up
RUN apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* && \
    rm /var/log/lastlog /var/log/faillog

WORKDIR /var/www