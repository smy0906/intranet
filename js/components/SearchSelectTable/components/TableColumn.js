import React from 'react';

const TableColumn = ({type, width, name, dataField}) => {
  return (
    <th type={type? type : 'string'} >
      {name}
    </th>
  );
};

export default TableColumn;
