import sys

def check_args():
    if len(sys.argv) != 2:
        print("Usage: python update.py [major/minor/render]")
        print("Defaulting to minor version change")
        return "minor"
    return sys.argv[1]

def get_version():
    with open("version.txt","r") as filehandle:
        version_txt = filehandle.readlines()[0].strip("\n")
        major,minor = [int(n) for n in version_txt.split(".")]
        return major,minor

def update_version(major, minor, ver):
    if ver == "major":
        major += 1
    if ver == "minor":
        minor += 1

    return f"{major}.{minor}"

def write_version(ver):
    with open("version.txt", "w") as filehandle:
        filehandle.writelines(ver)

def render_template(version):
    with open("kubernetes/app_deploy_template", "r") as filehandle:
        lines = filehandle.readlines()
        new_lines = []
        for line in lines:
            if "{{ version }}" in line:
                line = line.replace("{{ version }}", version)
            new_lines.append(line)

        with open("kubernetes/app_deploy.yaml","w") as filehandle:
            filehandle.writelines(new_lines)


def main():
    ver = check_args()

    if ver == "render":
        render_template(ver)
        return 0

    major, minor = get_version()
    new_ver = update_version(major, minor, ver)

    print(f"{major}.{minor} increased to {new_ver}")

    write_version(new_ver)

    render_template(new_ver)

if __name__ == "__main__":
    main()
