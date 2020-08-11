# Tasker project



Made by [Juan Salgado](https://github.com/jsalgadovaquer) & available on [GitHub](https://github.com/jsalgadovaquer/tasker).

## First install

Build the Docker containers : `docker-compose build`.  

Once this is done, you can run the containers : `docker-compose up -d`.  

At the first launch after building the containers, wait a few seconds for MySQL to launch properly.  

To install proper vendor libraries, you need to run  composer in php container: `docker-compose exec php composer install -d ./public`

To initiate te project DB you need to run the Yii2 command `migrate` to create the tables: `docker-compose exec php php ./public/yii migrate --interactive=0`                     

The project is now available if you go to the URL `localhost`  

## Usage Application
In this project we are able to manage tasks.

There's only one screen in this application.

In that screen you will see a full grid of tasks, to track their status.

You can also filter by `Task Name` or `Task Status`.

You can create a new task by: 
* Press the `+` green button at the to top right of the grid.
* Set a task name in the modal.
* Press the `Start` button.

When the task is started, it will appear in the grid with a timer as long as the task is not closed.

You can close the task by pressing the `Stop` button of the row of the selected task.

You can see a summary by pressing `Summary` button at the to top right of the grid.

The summary is generated in on page load and it hasn't timer set. 

## Usage commands

Get a list of commands: `docker-compose exec php php ./public/yii`

You can start new task by commands: `docker-compose exec php php ./public/yii task start {Task Name}`

You can end existing tasks by commands: `docker-compose exec php php ./public/yii task end {Task Name}`

Get a list list of all the tasks with their status, start time, end time and total elapsed time: `docker-compose exec php php ./public/yii task/list`
    
