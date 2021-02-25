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
RUN apt-get update && apt-get install -y software-properties-common && add-apt-repository ppa:deadsnakes/ppa && apt-get update && \
        apt-get install -y --force-yes python3.6 python3.6-dev wget build-essential libpq-dev libssl-dev openssl libffi-dev zlib1g-dev && \
		apt-get clean && \
	    apt-get autoremove && \
	    rm -rf /var/lib/apt/lists/*

RUN wget https://bootstrap.pypa.io/get-pip.py && \
	python3.6 get-pip.py && \
	ln -s /usr/bin/python3.6 /usr/local/bin/python3 && \
	ln -s /usr/bin/python3.6 /usr/local/bin/python

# Install other useful Python packages using pip
RUN pip3 install --upgrade scikit-learn joblib numpy
	
# Create the execution binary for the entrypoint
RUN echo "#!/bin/sh\n cd /var/www; rm -f /var/run/apache2/apache2.pid; /usr/bin/apache2-foreground" > /run/startup.sh && \
    chmod a+x /run/startup.sh
    
