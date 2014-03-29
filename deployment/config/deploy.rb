require 'capistrano/ext/multistage'

# =============================================================================
# SCM OPTIONS
# =============================================================================
set :scm, :git
#set :scm_user, "username"      # optional
#set :scm_password, "password"  # optional
set :repository, "git@github.com:Firewall/Tipr.git"

# =============================================================================
# REQUIRED VARIABLES
# =============================================================================
set :current_dir, "release"

# =============================================================================
# SSH OPTIONS
# =============================================================================
set :user, "root"
set :use_sudo, false           # optional
set :ssh_options, { :forward_agent => true }

# =============================================================================
# STAGES
# =============================================================================
set :stages, ["development"]
set :default_stage, "development"
set :stage_dir, "config/deploy"

# =============================================================================
# SETUP
# =============================================================================
set :shared_children, %w(logs resources)
set :public_children, %w(img js css)
set :shared_folders, [
]

# =============================================================================
# RELEASE
# =============================================================================
set :keep_releases, 5
set :deploy_via, :copy
set :copy_cache, true

set :copy_exclude, [
    ".git",
    ".gitignore",
    "cache",
    "deployment",
    "logs",
    "phpunit.xml.dist",
    "**/tests",
    "resources",
    "**/.git",
    "**/.gitignore",
    "**/tests",
    "**/example",
    "**/doc",
    "**/documentation",
    "web/app_dev.php",
    "**/.idea"
]

# =============================================================================
# TASKS
# =============================================================================

before "deploy:install_vendors", "deploy:copy_vendors"
before "deploy:finalize_update", "deploy:install_vendors"

after "deploy:finalize_update", "deploy:warmup"

namespace :deploy do

    task :update_code, :except => { :no_release => true } do
      on_rollback { run "rm -rf #{release_path}; true" }
      strategy.deploy!
      finalize_update
    end

    task :finalize_update do
        transaction do
            run "chmod -R g+w #{latest_release}" if fetch(:group_writable, true)

            # mkdir -p is making sure that the directories are there for some SCM's that don't
            # save empty folders
            run <<-CMD
              mkdir -p #{latest_release}/cache/proxies
            CMD
            # change back to chmod -R +x when server rights have been fixed

            run <<-CMD
              mkdir -p #{latest_release}/app/cache
            CMD
            # change back to chmod -R +x when server rights have been fixed

            run <<-CMD
              rm -rf #{latest_release}/app/logs &&
              mkdir -p #{latest_release}/app/logs &&
              chmod 755 #{latest_release}/app/logs
            CMD


            shared_folders.map do |d|
              run "mkdir -p #{shared_path}/#{d}"
            end

            shared_children.map do |d|
              run "ln -s #{shared_path}/#{d.split('/').last} #{latest_release}/#{d}"
            end
        end
    end

    task :warmup do
        run "cd #{latest_release} && php app/console cache:clear -e #{application_env} --no-debug --no-warmup"
        run "cd #{latest_release} && php app/console cache:warmup -e #{application_env} --no-debug"
    end

    task :create_symlink, :except => { :no_release => true } do
        on_rollback do
          if previous_release
            run "rm -f #{current_path}; ln -s #{previous_release} #{current_path}; true"
          else
            logger.important "no previous release to rollback to, rollback of symlink skipped"
          end
        end

        run "rm -f #{current_path} && ln -s #{latest_release} #{current_path}"
    end

    task :restart do
        run "/usr/local/bin/restartapache"
    end

    task :htaccess do
        htaccess = <<-EOF
        <IfModule mod_rewrite.c>
            RewriteEngine On

            RewriteCond %{HTTPS} !=on
            RewriteCond %{SERVER_NAME} !api\.4411\.be$ [NC]
            RewriteRule ^/?(.*) https://%{SERVER_NAME}/$1 [R,L]

            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteRule ^(.*)$ app.php [QSA,L]
        </IfModule>
        EOF

        put htaccess, "#{latest_release}/web/.htaccess"
        end

    task :copy_vendors, :except => { :no_release => true } do
        logger.info "--> Copy vendor file from previous release"

        if previous_release
            run "vendorDir=#{previous_release}/vendor; if [ -d $vendorDir ] || [ -h $vendorDir ]; then cp -a $vendorDir #{latest_release}/vendor; fi;"
        else
            logger.info "no previous release to copy vendors from"
        end
    end

    task :install_vendors, :except => { :no_release => true } do
        logger.info "--> Install vendors"
        run "cd #{latest_release} && curl -s http://getcomposer.org/installer | php && php composer.phar install"
    end
end
