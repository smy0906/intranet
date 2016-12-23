import React from 'react';
import { Checkbox } from 'react-bootstrap';
import TableColumn from './TableColumn';

const TableHead = ({colDefines, onSelectAll}) => {
  const cols =  colDefines.map((data, i) =>
    <TableColumn key={i}
                 type={data.type}
                 name={data.name}
                 dataField={data.dataField}/>
  );

  return (
    <thead>
      <tr>
        <th><Checkbox onChange={e => onSelectAll(e)}/></th>
        {cols}
        <th/>
      </tr>
    </thead>
  );
};

export default TableHead;
