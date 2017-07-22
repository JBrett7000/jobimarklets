<?php
/**
 * Author: Ndifreke Ekott < ndy40.ekott@gmail.com >
 * File: TagLogic.php
 * Date: 11/06/2017
 * Time: 18:32
 */

namespace Jobimarklets\Logic;


use Jobimarklets\entity\Tag;
use Jobimarklets\Exceptions\TagException;
use Jobimarklets\Repository\TagRepository;

class TagLogic extends AbstractLogic
{

    public function __construct(TagRepository $repo)
    {
        parent::__construct($repo);
    }

    /**
     *  Update Tag.
     *
     * @param Tag $tag
     * @throws TagException
     * @return mixed
     */
    public function update(Tag $tag)
    {
        $validator = $tag->validate();

        if ($validator->fails()) {
            $errors =  array_reduce(
                $validator->errors()->all(),
                function ($context, $item) {
                    $context .= $item;

                    return $context;
                },
                ''
            );

            throw new TagException('Error updating Tag - ' . $errors);
        }

        return $this->repository->update($tag);
    }

    public function create(Tag $tag)
    {
        $validator = $tag->validate();

        if ($validator->fails()) {
            $errors =  array_reduce(
                $validator->errors()->all(),
                function ($context, $item) {
                    $context .= $item;

                    return $context;
                },
                ''
            );

            throw new TagException('Error creating Tag - ' . $errors);
        }

        return $this->repository->create($tag);
    }


}