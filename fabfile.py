import os

from fabric.api import *
from fabric import colors
from fabric.operations import put


"""
Base configuration
"""
env.project_name = 'inndev'
env.ignore_files_containing = [
    '.py',
    '.pyc',
    '.git',
    'requirements.txt',
]


# Environments
def production():
    """
    Work on production environment
    """
    env.settings = 'production'
    env.hosts = []
    env.user = ''
    env.password = ''


def staging():
    """
    Work on staging environment
    """
    env.settings = 'staging'
    env.hosts = []
    env.user = ''
    env.password = ''

def stable():
    """
    Work on stable branch.
    """
    print(colors.green('On stable'))
    env.branch = 'stable'


def master():
    """
    Work on development branch.
    """
    print(colors.yellow('On master'))
    env.branch = 'master'


def branch(branch_name):
    """
    Work on any specified branch.
    """
    print(colors.red('On %s' % branch_name))
    env.branch = branch_name


def deploy():
    """
    Deploy local copy of repository to target environment
    """
    require('settings', provided_by=["production", "staging", ])
    require('branch', provided_by=[master, stable, branch, ])

    local('git checkout %s' % env.branch)
    local('git submodule update --init --recursive')

    for f in find_file_paths('.'):
        put(local_path=f[0], remote_path='/%s' % f[0])


def find_file_paths(directory):
    """
    A generator function that recursively finds all files in the
    upload directory.
    """
    for root, dirs, files in os.walk(directory):
        rel_path = os.path.relpath(root, directory)
        for f in files:
            # Skip dot files
            if f.startswith('.'):
                continue

            if rel_path == '.':
                one, two = f, os.path.join(root, f)
            else:
                one, two = os.path.join(rel_path, f), os.path.join(root, f)

            # Skip any files we explicitly say to ignore
            skip = False
            for s in env.ignore_files_containing:
                if s in one or s in two:
                    skip = True
                    break

            if skip:
                continue

            yield(one, two)
