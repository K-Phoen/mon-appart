set :application, "Appart"
set :domain,      "appart.kevingomez.fr"
set :deploy_to,   "/home/kevin/appart.kevingomez.fr"
set :user,        "kevin"
set :deploy_via,  :rsync_with_remote_cache
set :app_path,    "app"
set :app_config_file, "parameters.yml"
set :ssh_options, {:forward_agent => true, :port => 4222}

set :repository,  "git@bitbucket.org:kphoen/monappart.git"
set :branch,      "master"
set :scm,         :git
set :copy_exclude, [ ".git" ]

server "178.32.220.164", :app, :web, :db, :primary => true

# ORM
set :model_manager, "doctrine"

set :use_sudo,      false
set :keep_releases, 2
set :shared_children, [ app_path + "/logs" ]
set :shared_files, [ "app/config/parameters.yml" ]

after "deploy", "deploy:cleanup"

# permissions
set :permission_method, :acl
before 'symfony:assetic:dump', 'deploy:set_permissions'
before 'symfony:assets:install', 'deploy:set_permissions'

# vendors management
set :use_composer, true
set :update_vendors, false

# assets management
set :dump_assetic_assets, true
set :assets_symlinks, true

# IMPORTANT = 0
# INFO      = 1
# DEBUG     = 2
# TRACE     = 3
# MAX_LEVEL = 3
logger.level = Logger::DEBUG
