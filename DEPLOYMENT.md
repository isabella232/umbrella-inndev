# Command line deployment

## Setup

1. Setup python dev environment

    $ sudo easy_install pip
    $ sudo pip install virtualenv
    $ sudo pip install virtualenvwrapper
    $ echo 'source /usr/local/bin/virtualenvwrapper.sh' >> ~/.zshrc

2. If you're using Mavericks (10.9), you'll need to add some compiler flags to your profile:

    $ echo "export CFLAGS=-Qunused-arguments\nexport CPPFLAGS=-Qunused-arguments" >> ~/.zshrc

Note: substitute `~/.zshrc` with the path to the rc file for your shell. Bash, for example: `~/.bashrc`.

3. Open a new terminal window or tab and create a virtual environment for your project:

    $ mkvirtualenv inndev --no-site-packages

4. Install the required libraries:

    $ pip install -r requirements.txt


## Settings

The `fabfile.py` file contains sections for staging and production settings. You'll need to fill in `env.hosts`, `env.user` and `env.password` before any of the deployment commands will work.

An example for the `env.hosts` definition:

    env.hosts = ['inndev.wpengine.com', ]


## Usage

To see a complete list of available commands:

    $ fab -l

To deploy to your staging environment:

    $ fab staging master deploy

And production:

    $ fab production master deploy

To switch to a different branch and deploy

    $ fab staging newfeaturebranchname deploy
