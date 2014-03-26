# DEVELOPMENT-specific deployment configuration
# please put general deployment config in config/deploy.rb

server "tipr.be", :app, :web, :db, :primary => true

set :application, "tipr.be"
set :application_env, "dev"
set :branch, "master"
set :user, "root"
set :deploy_to, "/home/wwwroot/default"

namespace :deploy do
    task :restart do
        #run "sudo /etc/init.d/httpd restart"
        #run "sudo /etc/init.d/php-fpm restart"
        #run "cd #{latest_release} && app/console memcache:clear -e #{application_env}"
    end
end
