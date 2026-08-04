import sys
import yaml

def check_args():
    if len(sys.argv) != 2:
        print("Usage: python update.py [major/minor/render]")
        print("Defaulting to minor version change")
        return "minor"
    return sys.argv[1]


def get_yaml_file():
    with open("kubernetes/app_deploy.yaml", "r") as filehandle:
        loaded_yaml = yaml.safe_load(filehandle)
    return loaded_yaml

def get_image_ver_from_yaml(yaml_file):
    image_and_ver = yaml_file["spec"]["template"]["spec"]["containers"][0]["image"]
    image,ver = image_and_ver.split(":")

    return (image,ver)

def get_major_minor(text):
    major,minor = [int(n) for n in text.split(".")[:2]]
    return major,minor

def write_yaml_to_file(yaml_dict: dict):
    with open("kubernetes/app_deploy_test.yaml", "w+") as filehandle:
        yaml.dump(yaml_dict, filehandle)

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


def update_yaml(yaml_dict, old_image, new_image):

    if type(yaml_dict) is dict:
        # iterate over keys and their values
        for key, value in yaml_dict.items():
            # if the value is a dict, recurse on that
            if type(value) is dict:
                yaml_dict[key] = update_yaml(value, old_image, new_image)

            if type(value) is list:
                yaml_dict[key] = [update_yaml(v, old_image, new_image) for v in value]

            else: # so either a string or int
                if value == old_image:
                    yaml_dict[key] = new_image

    # this branch only has to exist in case we loop over a list of str/int in the list handler above
    if type(yaml_dict) is str:
        if yaml_dict == old_image:
            return new_image

    return yaml_dict

def main():

    ver = check_args()

    yaml_file = get_yaml_file()

    image, version = get_image_ver_from_yaml(yaml_file)
    old_image = f"{image}:{version}"

    major, minor = get_major_minor(version)

    new_ver = update_version(major, minor, ver)

    new_image = f"{image}:{new_ver}"

    print(f"Updating to: {new_image}")

    new_yaml_dict = update_yaml(yaml_file, old_image, new_image)
    print(new_yaml_dict)

    write_yaml_to_file(new_yaml_dict)

    # ver = check_args()
    #
    # if ver == "render":
    #     major, minor = get_version()
    #     ver = f"{major}.{minor}"
    #     print(ver)
    #     render_template(ver)
    #     return 0
    #
    # major, minor = get_version()
    # new_ver = update_version(major, minor, ver)
    #
    # print(f"{major}.{minor} increased to {new_ver}")
    #
    # write_version(new_ver)
    #
    # render_template(new_ver)

if __name__ == "__main__":
    main()
