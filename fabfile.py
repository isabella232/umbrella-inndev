from tools.fablib import *

"""
Base configuration
"""
env.project_name = 'inndev'
env.file_path = '.'


# Environments
def production():
    """
    Work on production environment
    """
    env.settings = 'production'
    env.hosts = [os.environ['INNDEV_PRODUCTION_SFTP_HOST'], ]
    env.user = os.environ['INNDEV_PRODUCTION_SFTP_USER']
    env.password = os.environ['INNDEV_PRODUCTION_SFTP_PASSWORD']


def staging():
    """
    Work on staging environment
    """
    env.settings = 'staging'
    env.hosts = [os.environ['INNDEV_STAGING_SFTP_HOST'], ]
    env.user = os.environ['INNDEV_STAGING_SFTP_USER']
    env.password = os.environ['INNDEV_STAGING_SFTP_PASSWORD']
