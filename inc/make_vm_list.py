#!/usr/bin/env python3

from websocket import create_connection
import random
import json

DOMAIN = "cacophony.srcf.net"
USER = "server-listing"
PASSWORD = open("/societies/srcf-web/.xo-user").read().strip()

ws = create_connection("wss://%s/api/" % DOMAIN, header=[
    "Content-Type: application/json",
    "Accept: application/json-rc",
    ])

def send(method: str, params: dict):
    msg_id = random.randint(1, 9007199254740991)
    message = {
        "jsonrpc": "2.0",
        "method": method,
        "params": params,
        "id": msg_id,
    }
    ws.send(json.dumps(message))
    message = None

    while True:
        message = json.loads(ws.recv())
        if "id" not in message:
            continue

        if message["id"] != msg_id:
            raise ValueError("Unexpected reply:\n{}".format(message))

        break

    if "result" in message:
        return message["result"]
    else:
        raise ValueError("Error reply from RPC call:\n{}".format(message))

send("session.signIn", {
    "email": USER,
    "password": PASSWORD,
})

pools = send("xo.getAllObjects", {"filter": { "type": "pool" }})
for pool in pools.values():
    pool["vms"] = []

vm_dict = send("xo.getAllObjects", {"filter": { "type": "VM", "tags": ["list"] }})

for vm in vm_dict.values():
    pools[vm["$pool"]]["vms"].append({
        "label": vm["name_label"],
        "desc": vm.get("name_description", ""),
        "ip": vm.get("mainIpAddress", "?"),
        "cpu": vm["CPUs"]["number"],
        "ram": '%.1f' % (vm["memory"]["size"] / (1 << 30)),
        "os": vm.get("os_version", {}).get("name", "?"),
        })

pools = list(pools.values())
pools.sort(key=lambda x: x["name_label"])

for pool in pools:
    if len(pool["vms"]) == 0:
        continue

    pool["vms"].sort(key=lambda x: (x["label"], x["desc"], x["ip"]))

    print("<h2>{}</h2>".format(pool["name_label"]))
    if pool.get("name_description", "") != "":
        print("<p>{}</p>".format(pool["name_description"]))

    print("""\
<table class="table">
  <thead>
    <tr>
      <th>Name</th>
      <th>Specs</th>
      <th>Operating System</th>
      <th>Description</th>
    </tr>
  </thead>
  <tbody>""")

    for vm in pool["vms"]:
        print("    <tr><td>{label}</td><td>{cpu} &times; <i class='fa fa-microchip'></i>&emsp;{ram} GiB <i class='fa fa-sliders'></i></td><td>{os}</td><td>{desc}</td></tr>".format(**vm))

    print("""\
  </tbody>
</table>""")

ws.close()
