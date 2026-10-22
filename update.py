import sys
import yaml as yaml_lib


class IMAGE:
    def __init__(self, image_string, txt_path):
        self.image = image_string.split(":")[0]
        self.major = int(image_string.split(":")[1].split(".")[0])
        self.minor = int(image_string.split(":")[1].split(".")[1])

        self.path = txt_path

        self.get_version_from_txt()
        self.get_higher_of_yaml_or_txt()

        print(self.create_image_string())

    def create_image_string(self):
        return f"{self.image}:{self.major}.{self.minor}"

    def bump(self, ver):
        if ver == "major":
            self.major += 1
        elif ver == "minor":
            self.minor += 1
        else:
            print("version to bump not specified, defaulting to minor")
            self.minor += 1

    def get_version_from_txt(self, path=None):
        if not path:
            path = self.path
        with open(path,"r") as filehandle:
            version_txt = filehandle.readlines()[0].strip("\n")
            self.txt_major = int(version_txt.split(".")[0])
            self.txt_minor = int(version_txt.split(".")[1])

    def get_higher_of_yaml_or_txt(self):
        # this should not be necessary, but catering for
        # if there's manual intervention
        self.major = max(self.major, self.txt_major)
        self.minor = max(self.minor, self.txt_minor)

    def write_txt(self, path=None):
        if not path:
            path = self.path
        with open(path, "w") as filehandle:
            filehandle.writelines(f"{self.major}.{self.minor}")


class YAML:
    def __init__(self, path):
        self.path = path
        self.read_yaml(self.path)


    def read_yaml(self, path=None):
        if not path:
            path = self.path
        with open(path, "r") as filehandle:
            loaded_yaml = yaml_lib.safe_load(filehandle)
            self.yaml = loaded_yaml
            self.image = self.yaml["spec"]["template"]["spec"]["containers"][0]["image"]

    def update_yaml(self, old_image, new_image):
        self.yaml = update_yaml(self.yaml, old_image, new_image)
        print(self.yaml)

    def write_yaml(self, path=None):
        if not path:
            path = self.path
        with open(path, "w") as filehandle:
            yaml_lib.dump(self.yaml, filehandle)


def check_args():
    if len(sys.argv) != 2:
        print("Usage: python update.py [major/minor/render]")
        print("Defaulting to minor version change")
        return "minor"
    return sys.argv[1]

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

    # load yaml and image version.txt
    yaml = YAML(path="kubernetes/app_deploy.yaml")
    image = IMAGE(image_string=yaml.image, txt_path="version.txt")
    old_image = image.create_image_string()

    # increase version of image
    image.bump(ver)
    new_image = image.create_image_string()

    print(f"Updated {old_image} to {new_image}")

    # save new version to text file
    image.write_txt("version.txt")

    # update the yaml file, and save new yaml
    yaml.update_yaml(yaml.image, new_image)
    yaml.write_yaml(path="kubernetes/app_deploy.yaml")

if __name__ == "__main__":
    main()
