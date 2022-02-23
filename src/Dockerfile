########################################################
# Dockerfile for project: classification-battle
########################################################
# This dockerfile was created based on:
# - https://hub.docker.com/r/renangbarreto/apache-php5-phantomjs-docker/
# - https://hub.docker.com/r/renangbarreto/deeplearning-docker/
########################################################

FROM renangbarreto/apache-php5-phantomjs-docker:latest

MAINTAINER Renan Gomes <email {at} renangomes.com>

# Install useful Python packages using apt-get 
RUN rm -f /etc/apt/sources.list.d/pgdg.list /etc/apt/sources.list.d/pgdg.list.save
RUN apt-get update && \
    apt-get install -y software-properties-common wget build-essential libpq-dev libssl-dev openssl libffi-dev zlib1g-dev && \
	apt-get install -y --reinstall ca-certificates && \
	apt-get clean && \
	apt-get autoremove && \
	rm -rf /var/lib/apt/lists/*

# Install python
RUN wget https://www.python.org/ftp/python/3.6.15/Python-3.6.15.tgz && \
	tar -xvf Python-3.6.15.tgz && \
	cd Python-3.6.15 && \
	./configure --enable-optimizations && \
	make -j8 && \
	make install && \
	cd .. && \
	rm -Rf Python-3.6.15

# Install pip
RUN wget https://bootstrap.pypa.io/pip/3.6/get-pip.py && \
	python3.6 get-pip.py 

# Install other useful Python packages using pip
RUN pip3 install --upgrade scikit-learn joblib numpy

# Create the execution binary for the entrypoint
RUN echo "#!/bin/sh\n cd /var/www; rm -f /var/run/apache2/apache2.pid; /usr/bin/apache2-foreground" > /run/startup.sh && \
    chmod a+x /run/startup.sh
