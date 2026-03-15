FROM centos:7

# Fix yum repos — CentOS 7 is EOL, mirrors are gone, use vault instead
RUN sed -i 's/mirror.centos.org/vault.centos.org/g' /etc/yum.repos.d/*.repo && \
    sed -i 's/^#.*baseurl=http/baseurl=http/g' /etc/yum.repos.d/*.repo && \
    sed -i 's/^mirrorlist=http/#mirrorlist=http/g' /etc/yum.repos.d/*.repo

# Install required repositories
RUN yum install -y epel-release && \
    yum install -y https://rpms.remirepo.net/enterprise/remi-release-7.rpm && \
    yum install -y yum-utils && \
    yum-config-manager --enable remi-php74

# Install PHP 7.4 and required extensions
RUN yum install -y \
    php \
    php-cli \
    php-pgsql \
    php-pdo \
    php-mbstring \
    php-curl \
    php-json \
    php-openssl \
    php-xml

# Install PHPUnit
RUN curl -L https://phar.phpunit.de/phpunit-9.phar -o /usr/local/bin/phpunit && \
    chmod +x /usr/local/bin/phpunit

# Verify installations
RUN php -v && phpunit --version