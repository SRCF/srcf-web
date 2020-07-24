#!/usr/bin/python3

from srcf.database import queries
from srcf.database.schema import Member, Society

def count_members():
    query = queries.list_members()
    all_members = query.filter_by(member=True).count()
    users = query.filter_by(user=True).count()
    return {"members": all_members, "users": users}

def count_societies():
    query = queries.list_societies()
    all_societies = query.count()
    active_societies = query.filter(Society.admins.any()).count()
    inactive_societies = all_societies - active_societies
    return {"societies": all_societies, "active_societies": active_societies,
            "inactive_societies": inactive_societies}

def make_ssi():
    set_vars = {}
    set_vars.update(count_members())
    set_vars.update(count_societies())
    for key, value in set_vars.items():
        # escaping fortunately unnecessary
        print('<!--#set var="{0}" value="{1}" -->'.format(key, value))

if __name__ == "__main__":
    make_ssi()
