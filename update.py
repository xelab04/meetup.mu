import sys
from jinja2 import Template

def check_args():
    if len(sys.argv) != 2:
        print("Usage: python update.py [major/minor]")
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

def write_render(content):
    with open("kubernetes/app_deploy.yaml", "w") as filehandle:
        filehandle.write(content)

def render_template(version):
    with open("kubernetes/app_deploy_template.jinja", "r") as filehandle:
        template_content = "".join(filehandle.readlines())
        template = Template(template_content)
        rendered_content = template.render(version=version)

        write_render(rendered_content)

def main():
    ver = check_args()
    major, minor = get_version()
    new_ver = update_version(major, minor, ver)

    print(f"{major}.{minor} increased to {new_ver}")

    write_version(new_ver)

    render_template(new_ver)

if __name__ == "__main__":
    main()
