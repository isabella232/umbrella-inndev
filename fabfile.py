from tools.fablib import *

from fabric.api import task

"""
Base configuration
"""
env.project_name = 'inndev'
env.file_path = '.'
env.sftp_deploy = True

env.hipchat_token = os.environ['HIPCHAT_DEPLOYMENT_NOTIFICATION_TOKEN']
env.hipchat_room_id = os.environ['HIPCHAT_DEPLOYMENT_NOTIFICATION_ROOM_ID']


# Environments
@task
def production():
    """
    Work on production environment
    """
    env.settings = 'production'
    env.hosts = [os.environ['INNDEV_PRODUCTION_SFTP_HOST'], ]
    env.user = os.environ['INNDEV_PRODUCTION_SFTP_USER']
    env.password = os.environ['INNDEV_PRODUCTION_SFTP_PASSWORD']
    env.domain = 'inn.org'
    env.port = 2222


@task
def staging():
    """
    Work on staging environment
    """
    env.settings = 'staging'
    env.hosts = [os.environ['INNDEV_STAGING_SFTP_HOST'], ]
    env.user = os.environ['INNDEV_STAGING_SFTP_USER']
    env.password = os.environ['INNDEV_STAGING_SFTP_PASSWORD']
    env.domain = 'inndev.staging.wpengine.com'
    env.port = 2222

try:
    from local_fabfile import  *
except ImportError:
    pass
