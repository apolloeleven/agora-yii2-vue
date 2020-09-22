
const getTree = (departments, parentId = null) => {
  const nodes = [];
  for (const item of departments) {
    if (item.parent_id === parentId) {
      item.children = getTree(departments, item.id);
      nodes.push(item);
    }
  }

  return nodes
}

export function departmentsTree(state) {
  return getTree(state.departments.data, null)
}
