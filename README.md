TIPR
====

#Deployment

**Install capistrano via ruby gems**

```
> gem install capistrano -v 2.15.5
> gem install capistrano-ext
```

Navigate to deployment directory

**make sure ssh-agent is running and ssh key added**
```
> eval `ssh-agent`
> ssh-add
```


#Create database

```
> php app/console doctrine:database:create
> php app/console doctrine:schema:create
```
