import { scHelpers } from "~/assets/js/utils";
const { uniqueID } = scHelpers;

export const menuEntries = [
  {
    section_title: "Menu",
  },
  {
    id: uniqueID(),
    title: "Dashboard",
    page: "/dashboard",
    icon: "mdi mdi-view-dashboard-variant",
    roles: ["administrator", "user"],
  },
  {
    id: uniqueID(),
    title: "Users",
    page: "/users",
    icon: "mdi mdi-human-child",
    isOpen: false,
    level: 0,
    roles: ["administrator"],
  },
  {
    id: uniqueID(),
    title: "My Blogs",
    page: "/blogs",
    icon: "mdi mdi-blogger",
    isOpen: false,
    level: 0,
    roles: ["administrator", "user"],
  }
];


