FROM helder/php-5.3

COPY docker-php-pecl-install /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-php-pecl-install
COPY apt-install /usr/local/bin/
RUN chmod +x /usr/local/bin/apt-install
COPY apt-purge /usr/local/bin/
RUN chmod +x /usr/local/bin/apt-purge

RUN apt-get update \
	&& apt-get install -y --no-install-recommends \
		libssl-dev \
		libicu-dev \
		libgd2-dev \
		libpng12-dev \
		libjpeg62-turbo-dev \
		libxpm-dev \
		libzip-dev \
		libbz2-dev \
		libxml2-dev \
		libmcrypt-dev \
		libmhash-dev \
		libxslt1-dev \
		gettext \
		gettext \
		joe \
		vim \
	&& apt-get clean \
	&& rm -r /var/lib/apt/lists/* \
	&& ln -s /usr/lib/x86_64-linux-gnu/libXpm.so /usr/lib/ \
	&& ln -s /usr/lib/x86_64-linux-gnu/libxslt.so /usr/lib/ \
	&& mkdir -pv /usr/include/freetype2/freetype \
	&& ln -sf /usr/include/freetype2/freetype.h /usr/include/freetype2/freetype/freetype.h \
	&& docker-php-ext-configure bz2 \
	&& docker-php-ext-install bz2 \
	&& docker-php-ext-configure exif \
	&& docker-php-ext-install exif \
	&& docker-php-ext-configure ftp \
	&& docker-php-ext-install ftp \
	&& docker-php-ext-configure gettext --with-gettext=/usr/lib/ \
	&& docker-php-ext-install gettext \
	&& docker-php-ext-configure gd --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/  --with-xpm-dir=/usr/lib/ --enable-gd-native-ttf --with-freetype-dir=/usr/include/freetype/  \
	&& docker-php-ext-install gd \
	&& docker-php-ext-configure intl \
	&& docker-php-ext-install intl \
	&& docker-php-ext-configure mbstring \
	&& docker-php-ext-install mbstring \
	&& docker-php-ext-configure mcrypt --with-mcrypt=/usr/lib/ --disable-posix-threads \
	&& docker-php-ext-install mcrypt \
	&& docker-php-ext-configure mysqli --with-mysqli=mysqlnd \
	&& docker-php-ext-install mysqli \
	&& docker-php-ext-configure xsl --with-xsl=/usr/lib/ \
	&& docker-php-ext-install pdo_mysql \
	&& docker-php-ext-install xsl \
	&& docker-php-ext-configure soap \
	&& docker-php-ext-install soap \
	&& docker-php-ext-configure zip \
	&& docker-php-ext-install zip 

RUN docker-php-pecl-install uploadprogress

RUN apt-install zlib1g-dev libicu-dev g++ && \
    docker-php-ext-configure intl && \
    docker-php-ext-install intl && \
    apt-purge g++
    
ENV USE_XDEBUG yes
ENV XDEBUG_VERSION 2.2.7
RUN docker-php-pecl-install xdebug-$XDEBUG_VERSION && \
    echo "xdebug.remote_enable=on\nxdebug.remote_connect_back=on" > /usr/local/etc/php/conf.d/xdebug.ini && \
    mkdir -p /usr/local/etc/php/xdebug.d && \
    mv /usr/local/etc/php/conf.d/*xdebug.ini /usr/local/etc/php/xdebug.d/
    
# Include composer
RUN apt-install git rsync zlib1g-dev && \
    docker-php-ext-install zip

ENV COMPOSER_HOME /var/www/.composer
ENV COMPOSER_VERSION 1.3.1
ENV PATH vendor/bin:$COMPOSER_HOME/vendor/bin:$PATH

RUN curl -sS https://getcomposer.org/installer | php -- \
      --install-dir=/usr/local/bin \
      --filename=composer \
      --version=${COMPOSER_VERSION}

RUN mkdir -p $COMPOSER_HOME/cache && \
    chown -R www-data:www-data /var/www && \
    echo "phar.readonly = off" > /usr/local/etc/php/conf.d/phar.ini
VOLUME $COMPOSER_HOME/cache

# Add entrypoint
COPY init.d /docker-entrypoint-init.d/
COPY docker-entrypoint.sh /

#Copy php.ini
COPY php.ini /usr/local/etc/php/

#Enable xDebug
RUN cp /usr/local/etc/php/xdebug.d/* /usr/local/etc/php/conf.d/.
COPY xdebug.ini /usr/local/etc/php/conf.d/

#Enable .cgi files
RUN sed -i '/;security.limit_extensions = .php .php3 .php4 .php5/c\security.limit_extensions = .php .cgi' /usr/local/etc/php-fpm.d/www.conf

EXPOSE 80
RUN chmod +x /docker-entrypoint.sh 
ENTRYPOINT ["/docker-entrypoint.sh"]
